<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RekognitionController extends Controller
{
    protected $rekognition;
    protected $s3;
    protected $bucket;
    protected $collectionId;

    /**
     * Construtor do controller
     */
    public function __construct()
    {
        $this->bucket = env('AWS_BUCKET');
        $this->collectionId = env('AWS_REKOGNITION_COLLECTION_ID', 'minha-colecao-de-faces');
        
        $this->rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Exibe a página de upload (apenas para admin)
     */
    public function showUploadForm()
    {
        return view('rekognition.upload');
    }

    /**
     * Exibe a página de busca por selfie
     */
    public function showSearchForm()
    {
        return view('rekognition.search');
    }

    /**
     * Exibe a galeria de fotos
     */
    public function showGallery()
    {
        try {
            $objects = $this->s3->listObjects([
                'Bucket' => $this->bucket,
                'Prefix' => 'uploads/',
            ]);

            $images = [];
            if (isset($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    if (Str::endsWith($object['Key'], ['.jpg', '.jpeg', '.png'])) {
                        $images[] = [
                            'key' => $object['Key'],
                            'url' => $this->s3->getObjectUrl($this->bucket, $object['Key']),
                            'size' => $object['Size'],
                            'lastModified' => $object['LastModified'],
                        ];
                    }
                }
            }

            return view('rekognition.gallery', ['images' => $images]);
        } catch (AwsException $e) {
            return back()->with('error', 'Erro ao listar imagens: ' . $e->getMessage());
        }
    }

    /**
     * Faz upload de imagens para o S3 e indexa no Rekognition
     */
    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);
    
        $uploadedFiles = [];
        $errors = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $filename = 'uploads/' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
    
                    // Upload para o S3
                    $result = $this->s3->putObject([
                        'Bucket' => $this->bucket,
                        'Key' => $filename,
                        'SourceFile' => $image->getRealPath(),
                        'ContentType' => $image->getMimeType(),
                        // 'ACL' => 'public-read',
                    ]);
    
                    $uploadedFiles[] = [
                        'filename' => $filename,
                        'url' => $result['ObjectURL'],
                    ];
    
                    // Indexar faces no Rekognition
                    $this->rekognition->indexFaces([
                        'CollectionId' => $this->collectionId,
                        'ExternalImageId' => basename($filename),
                        'Image' => [
                            'S3Object' => [
                                'Bucket' => $this->bucket,
                                'Name' => $filename,
                            ],
                        ],
                        'DetectionAttributes' => ['ALL'],
                    ]);
                } catch (AwsException $e) {
                    $errors[] = $image->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }
        }
    
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'uploadedFiles' => $uploadedFiles,
            ], 422);
        }
    
        return response()->json([
            'success' => true,
            'uploadedFiles' => $uploadedFiles,
        ]);
    }
    

    /**
     * Busca por selfie usando Rekognition
     */
    public function searchBySelfie(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $image = $request->file('selfie');
            $imageBytes = file_get_contents($image->getRealPath());
            
            // Buscar faces similares
            $result = $this->rekognition->searchFacesByImage([
                'CollectionId' => $this->collectionId,
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
                'MaxFaces' => 10,
                'FaceMatchThreshold' => 80,
            ]);

            $matches = [];
            if (isset($result['FaceMatches']) && count($result['FaceMatches']) > 0) {
                foreach ($result['FaceMatches'] as $match) {
                    $externalImageId = $match['Face']['ExternalImageId'];
                    $matches[] = [
                        'key' => $externalImageId,
                        'url' => "https://compil3rtestbucket.s3.amazonaws.com/uploads/{$externalImageId}",
                       // 'url' => str_replace('uploads_', 'uploads/', $this->s3->getObjectUrl($this->bucket, $externalImageId)),
                        'similarity' => $match['Similarity'],
                    ];
                }
            }

            // dd($matches);

            return view('rekognition.results', ['matches' => $matches]);
        } catch (AwsException $e) {
            return back()->with('error', 'Erro ao buscar: ' . $e->getMessage());
        }
    }

    /**
     * Download de uma imagem específica
     */
    public function downloadImage($key)
    {
        try {
            $result = $this->s3->getObject([
                'Bucket' => $this->bucket,
                //set first _ to / in key
                'Key' => "uploads/{$key}"
            ]);

            $filename = basename($key);
            return response($result['Body'], 200)
                ->header('Content-Type', $result['ContentType'])
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (AwsException $e) {
            return back()->with('error', 'Erro ao baixar imagem: ' . $e->getMessage());
        }
    }


    public function downloadMultiple(Request $request)
    {
        $keys = $request->input('keys', []);
        if (empty($keys)) {
            return back()->with('error', 'Nenhuma imagem selecionada.');
        }
    
        $zipFile = storage_path('app/public/download_' . time() . '.zip');
        $zip = new \ZipArchive();
    
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            return back()->with('error', 'Não foi possível criar o arquivo zip.');
        }
    
        $addedFiles = 0;
    
        foreach ($keys as $key) {
            try {
                // Corrigido: prefixo 'uploads/' incluído
                $object = $this->s3->getObject([
                    'Bucket' => $this->bucket,
                    'Key' => "uploads/{$key}",
                ]);
    
                // Nome limpo no ZIP
                $zip->addFromString(basename($key), $object['Body']);
                $addedFiles++;
    
            } catch (\Exception $e) {
                \Log::warning("Erro ao baixar {$key}: " . $e->getMessage());
                continue;
            }
        }
    
        $zip->close();
    
        if ($addedFiles === 0 || !file_exists($zipFile)) {
            return back()->with('error', 'Não foi possível adicionar nenhuma imagem ao arquivo ZIP.');
        }
    
        return response()->download($zipFile)->deleteFileAfterSend(true);
    }
    
public function deleteAllFaces()
{
    try {
        $faceIds = [];
        $params = [
            'CollectionId' => $this->collectionId,
            'MaxResults' => 4096,
        ];

        do {
            $response = $this->rekognition->listFaces($params);
            $faceIds = array_merge($faceIds, array_map(fn($face) => $face['FaceId'], $response['Faces']));
            $params['NextToken'] = $response['NextToken'] ?? null;
        } while (!empty($params['NextToken']));

        if (empty($faceIds)) {
            return response()->json(['message' => 'Nenhuma face encontrada para deletar.']);
        }

        $deletedCount = 0;
        foreach (array_chunk($faceIds, 1000) as $batch) {
            $deleteResponse = $this->rekognition->deleteFaces([
                'CollectionId' => $this->collectionId,
                'FaceIds' => $batch,
            ]);
            $deletedCount += count($deleteResponse['DeletedFaces']);
        }

        return response()->json(['message' => "$deletedCount faces deletadas com sucesso."]);
    } catch (\Exception $e) {
        \Log::error("Erro ao deletar faces do Rekognition: " . $e->getMessage());
        return response()->json(['message' => 'Erro ao deletar faces: ' . $e->getMessage()], 500);
    }
}


}
