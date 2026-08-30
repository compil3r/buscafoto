<?php

namespace App\Http\Controllers;

use Aws\Exception\AwsException;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            return back()->with('error', $this->friendlyAwsErrorMessage($e, 'Não foi possível carregar a galeria agora. Tente novamente em instantes.'));
        }
    }

    /**
     * Traduz uma exceção da AWS em uma mensagem amigável para o usuário,
     * em vez de expor o erro técnico bruto retornado pela API.
     */
    private function friendlyAwsErrorMessage(AwsException $e, string $fallback = 'Não foi possível concluir a operação agora. Tente novamente em instantes.'): string
    {
        $code = $e->getAwsErrorCode();
        $message = $e->getAwsErrorMessage() ?? '';

        if ($code === 'InvalidParameterException' && str_contains($message, 'no faces')) {
            return 'Não encontramos um rosto nessa foto. Tire a selfie com boa iluminação, olhando de frente para a câmera, e tente novamente.';
        }

        return match ($code) {
            'InvalidImageFormatException' => 'Formato de imagem não suportado. Envie uma foto em JPEG ou PNG.',
            'ImageTooLargeException' => 'Essa imagem é muito grande. Tente uma foto menor.',
            'ProvisionedThroughputExceededException', 'ThrottlingException' => 'O sistema está muito ocupado agora. Aguarde alguns segundos e tente novamente.',
            'AccessDeniedException', 'UnrecognizedClientException' => 'Não foi possível concluir a operação por um problema de configuração no servidor. Avise um administrador.',
            'ResourceNotFoundException' => 'Não encontramos o acervo desse evento no servidor. Avise um administrador.',
            default => $fallback,
        };
    }

    /**
     * Faz upload de uma imagem JPEG para o S3 e indexa no Rekognition.
     * Uma requisição = um arquivo (fila no cliente), evitando POST único grande.
     *
     * Validação manual (sem regras file/image do Laravel) para mensagens claras e
     * para evitar a falha genérica "validation.uploaded" quando o PHP rejeita o upload.
     */
    public function uploadImages(Request $request)
    {
        $check = $this->validateIncomingJpeg($request);
        if (isset($check['error'])) {
            return response()->json([
                'success' => false,
                'errors' => [$check['error']],
            ], 422);
        }

        $uploadResult = $this->processSingleImageUpload($check['file']);

        if (isset($uploadResult['error'])) {
            return response()->json([
                'success' => false,
                'errors' => [$uploadResult['error']],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Imagem enviada e indexada.',
            'uploadedFiles' => [$uploadResult['uploaded']],
        ]);
    }

    /**
     * @return array{file: UploadedFile}|array{error: string}
     */
    private function validateIncomingJpeg(Request $request): array
    {
        if (! $request->hasFile('image')) {
            return ['error' => 'Nenhum arquivo recebido no campo image. Se as fotos forem grandes, o PHP costuma descartar o POST inteiro: aumente upload_max_filesize e post_max_size no php.ini (ex.: 64M), reinicie o PHP e tente de novo.'];
        }

        /** @var UploadedFile $file */
        $file = $request->file('image');

        $phpError = (int) $file->getError();
        if ($phpError !== UPLOAD_ERR_OK) {
            return ['error' => $this->uploadPhpErrorMessage($phpError)];
        }

        if (! $file->isValid()) {
            return ['error' => 'Upload inválido: '.$file->getErrorMessage()];
        }

        $maxBytes = 51200 * 1024;
        if ($file->getSize() > $maxBytes) {
            return ['error' => 'Arquivo maior que 50 MB.'];
        }

        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, ['jpg', 'jpeg'], true)) {
            return ['error' => 'Use apenas arquivos .jpg ou .jpeg.'];
        }

        $path = $file->getRealPath();
        if ($path === false || ! is_readable($path)) {
            return ['error' => 'Não foi possível ler o arquivo enviado.'];
        }

        $mime = @mime_content_type($path);
        $imageInfo = @getimagesize($path);
        $isJpegMime = $mime === 'image/jpeg';
        $isJpegImage = $imageInfo !== false && ($imageInfo[2] ?? 0) === IMAGETYPE_JPEG;

        if (! $isJpegMime && ! $isJpegImage) {
            return ['error' => 'O arquivo precisa ser JPEG. Tipo detectado: '.($mime ?: 'desconhecido').'.'];
        }

        return ['file' => $file];
    }

    private function uploadPhpErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'O arquivo é maior que upload_max_filesize no php.ini. Defina upload_max_filesize=64M e post_max_size=64M, salve, reinicie o PHP e tente de novo.',
            UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o limite MAX_FILE_SIZE do formulário.',
            UPLOAD_ERR_PARTIAL => 'O upload foi interrompido (arquivo parcial). Tente novamente.',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'O servidor não tem pasta temporária para uploads (upload_tmp_dir).',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao gravar o arquivo no disco do servidor.',
            UPLOAD_ERR_EXTENSION => 'Uma extensão PHP bloqueou o upload.',
            default => 'Erro de upload PHP (código '.$code.').',
        };
    }

    /**
     * @return array{uploaded: array{filename: string, url: string}}|array{error: string}
     */
    private function processSingleImageUpload(UploadedFile $image): array
    {
        $ext = strtolower($image->getClientOriginalExtension());
        if (! in_array($ext, ['jpg', 'jpeg'], true)) {
            $ext = 'jpg';
        }

        $filename = 'uploads/'.time().'_'.Str::random(10).'.'.$ext;

        try {
            $result = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key' => $filename,
                'SourceFile' => $image->getRealPath(),
                'ContentType' => $image->getMimeType(),
            ]);

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
            return ['error' => $image->getClientOriginalName().': '.$this->friendlyAwsErrorMessage($e, 'Não foi possível enviar essa imagem agora.')];
        }

        return [
            'uploaded' => [
                'filename' => $filename,
                'url' => $result['ObjectURL'],
            ],
        ];
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
            return back()->with('error', $this->friendlyAwsErrorMessage($e, 'Não foi possível concluir a busca agora. Tente novamente em instantes.'));
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
                // set first _ to / in key
                'Key' => "uploads/{$key}",
            ]);

            $filename = basename($key);

            return response($result['Body'], 200)
                ->header('Content-Type', $result['ContentType'])
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        } catch (AwsException $e) {
            return back()->with('error', $this->friendlyAwsErrorMessage($e, 'Não foi possível baixar essa imagem agora.'));
        }
    }

    public function downloadMultiple(Request $request)
    {
        $keys = $request->input('keys', []);
        if (empty($keys)) {
            return back()->with('error', 'Nenhuma imagem selecionada.');
        }

        $zipFile = storage_path('app/public/download_'.time().'.zip');
        $zip = new \ZipArchive;

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
                \Log::warning("Erro ao baixar {$key}: ".$e->getMessage());

                continue;
            }
        }

        $zip->close();

        if ($addedFiles === 0 || ! file_exists($zipFile)) {
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
                $faceIds = array_merge($faceIds, array_map(fn ($face) => $face['FaceId'], $response['Faces']));
                $params['NextToken'] = $response['NextToken'] ?? null;
            } while (! empty($params['NextToken']));

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
            \Log::error('Erro ao deletar faces do Rekognition: '.$e->getMessage());

            return response()->json(['message' => 'Erro ao deletar faces: '.$e->getMessage()], 500);
        }
    }
}
