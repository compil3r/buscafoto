@extends('layouts.app')

@section('content')
<section id="upload-section" class="card p-4">
    <h2 class="mb-3"><i class="fas fa-upload"></i> Upload de Fotos</h2>
    <p class="text-muted">Arraste ou selecione imagens para indexar rostos na base de dados.</p>

    <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data" id="upload-form">
        @csrf
        <div id="dropzone" class="dropzone-area text-center mb-3">
            <p class="mb-2">Arraste as imagens aqui ou clique para selecionar</p>
            <input type="file" id="image-upload" name="images[]" accept="image/*" multiple hidden>
            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image-upload').click()">
                <i class="fas fa-folder-open"></i> Selecionar Imagens
            </button>
        </div>

        <div id="file-list-upload" class="file-list mb-3"></div>
        <div id="image-preview-upload" class="image-preview-grid mb-3"></div>

        <button type="submit" class="btn btn-secondary w-100" id="upload-button" disabled>
            <i class="fas fa-cloud-upload-alt"></i> Enviar Imagens
        </button>

        <div id="upload-status" class="status-message mt-3"></div>
    </form>
</section>

@if (session('customErrors'))
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach (session('customErrors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection


@section('styles')
<style>
    #upload-section {
        max-width: 700px;
        margin: 0 auto;
    }
    .dropzone-area {
        border: 2px dashed var(--primary-color);
        border-radius: 10px;
        padding: 40px 20px;
        cursor: pointer;
        background-color: #f9f9f9;
        transition: background-color 0.3s;
    }
    .dropzone-area.dragover {
        background-color: #e3f2fd;
    }
    .file-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .remove-file-btn {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }
    .image-preview-item img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection

@section('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        
        const uploadForm = document.getElementById('upload-form');
        const dropzone = document.getElementById('dropzone');
        const imageUploadInput = document.getElementById('image-upload');
        const fileListUpload = document.getElementById('file-list-upload');
        const imagePreviewUpload = document.getElementById('image-preview-upload');
        const uploadButton = document.getElementById('upload-button');
        const uploadStatus = document.getElementById('upload-status');
        let selectedFiles = [];

        const updateUI = () => {
            fileListUpload.innerHTML = '';
            imagePreviewUpload.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const listItem = document.createElement('div');
                listItem.className = 'file-list-item';
                listItem.innerHTML = `<span>${file.name}</span><button type="button" class="remove-file-btn" data-index="${index}">&times;</button>`;
                fileListUpload.appendChild(listItem);

                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview-item';
                    preview.innerHTML = `<img src="${e.target.result}" alt="preview">`;
                    imagePreviewUpload.appendChild(preview);
                };
                reader.readAsDataURL(file);
            });
            uploadButton.disabled = selectedFiles.length === 0;
        };

        fileListUpload.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-file-btn')) {
                const index = parseInt(e.target.dataset.index);
                selectedFiles.splice(index, 1);
                updateUI();
            }
        });

        const handleFiles = (files) => {
            [...files].forEach(file => {
                if (file.type.startsWith('image/')) selectedFiles.push(file);
            });
            updateUI();
        };

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        imageUploadInput.addEventListener('change', () => {
            handleFiles(imageUploadInput.files);
            imageUploadInput.value = null;
        });

        uploadForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (selectedFiles.length === 0) {
        uploadStatus.innerHTML = '<div class="alert alert-danger">Selecione ao menos uma imagem.</div>';
        return;
    }

    const formData = new FormData();
    selectedFiles.forEach(file => formData.append('images[]', file));

    uploadStatus.innerHTML = '<div class="alert alert-warning">Enviando imagens...</div>';
    uploadButton.disabled = true;

    try {
        const response = await fetch(uploadForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
        const html = await response.text();
        console.error("❌ Recebeu HTML inesperado:", html);
        return;
        }

        const result = await response.json();

        if (response.ok) {
            uploadStatus.innerHTML = `<div class="alert alert-success">${result.message || 'Imagens enviadas com sucesso!'}</div>`;
            selectedFiles = [];
            updateUI();
        } else {
            throw new Error(result.message || 'Erro ao enviar as imagens.');
        }
    } catch (error) {
        uploadStatus.innerHTML = `<div class="alert alert-danger">Erro: ${error.message}</div>`;
    } finally {
        uploadButton.disabled = selectedFiles.length === 0;
    }
});


    });
</script>
@endsection
