@extends('layouts.app')

@section('content')
<div class="page-head">
    <span class="eyebrow">Acervo do evento</span>
    <h2><i class="fas fa-upload"></i> Upload de fotos</h2>
    <p>Envie fotos em <strong>JPEG</strong> direto da câmera. Cada arquivo é enviado um por vez ao servidor.</p>
</div>

<section id="upload-section" class="card form-section">
    <p class="mono-tag" style="margin-bottom:22px;">Se todos os envios falharem, confira <code>upload_max_filesize</code> e <code>post_max_size</code> no <code>php.ini</code> (use pelo menos 64M).</p>

    <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data" id="upload-form">
        @csrf
        <div id="dropzone" class="dropzone-area mb-3">
            <i class="fas fa-film"></i>
            <p>Arraste os JPEG aqui ou clique para selecionar</p>
            <input type="file" id="image-upload" accept=".jpg,.jpeg,image/jpeg" multiple hidden>
            <button type="button" class="btn btn-outline btn-sm" id="pick-files-btn">
                <i class="fas fa-folder-open"></i> Selecionar JPEG
            </button>
        </div>

        <div id="file-list-upload" class="file-list mb-3"></div>
        <div id="image-preview-upload" class="image-preview-grid mb-3"></div>

        <div id="upload-progress-wrap" class="mb-3 upload-progress-hidden">
            <div class="progress-bar-outer">
                <div id="upload-progress-fill" class="progress-bar-fill" style="width: 0%"></div>
            </div>
            <p id="upload-progress-label" class="mono-tag mt-2 mb-0"></p>
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="upload-button" disabled>
            <i class="fas fa-cloud-upload-alt"></i> Enviar fila
        </button>

        <div id="upload-status" class="status-message mt-3"></div>
    </form>
</section>

@if (session('customErrors'))
    <div class="bf-alert bf-alert-danger">
        <ul class="mb-0">
            @foreach (session('customErrors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const uploadForm = document.getElementById('upload-form');
        const dropzone = document.getElementById('dropzone');
        const pickFilesBtn = document.getElementById('pick-files-btn');
        const imageUploadInput = document.getElementById('image-upload');
        const fileListUpload = document.getElementById('file-list-upload');
        const imagePreviewUpload = document.getElementById('image-preview-upload');
        const uploadButton = document.getElementById('upload-button');
        const uploadStatus = document.getElementById('upload-status');
        const progressWrap = document.getElementById('upload-progress-wrap');
        const progressFill = document.getElementById('upload-progress-fill');
        const progressLabel = document.getElementById('upload-progress-label');

        let selectedFiles = [];
        let isUploading = false;

        function isJpegFile(file) {
            const t = (file.type || '').toLowerCase();
            const n = (file.name || '').toLowerCase();
            return t === 'image/jpeg' || n.endsWith('.jpg') || n.endsWith('.jpeg');
        }

        const updateUI = () => {
            fileListUpload.innerHTML = '';
            imagePreviewUpload.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const listItem = document.createElement('div');
                listItem.className = 'file-list-item';
                listItem.dataset.index = String(index);
                listItem.innerHTML = `<span class="file-name">${file.name}</span><button type="button" class="remove-file-btn" data-index="${index}" ${isUploading ? 'disabled' : ''}>&times;</button>`;
                fileListUpload.appendChild(listItem);

                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview-item';
                    preview.innerHTML = `<img src="${e.target.result}" alt="">`;
                    imagePreviewUpload.appendChild(preview);
                };
                reader.readAsDataURL(file);
            });
            uploadButton.disabled = selectedFiles.length === 0 || isUploading;
        };

        fileListUpload.addEventListener('click', (e) => {
            if (isUploading) return;
            if (e.target.classList.contains('remove-file-btn')) {
                const index = parseInt(e.target.dataset.index, 10);
                selectedFiles.splice(index, 1);
                updateUI();
            }
        });

        const handleFiles = (files) => {
            const skipped = [];
            [...files].forEach((file) => {
                if (isJpegFile(file)) {
                    selectedFiles.push(file);
                } else {
                    skipped.push(file.name);
                }
            });
            if (skipped.length) {
                uploadStatus.innerHTML = `<div class="bf-alert bf-alert-warning">Ignorados (somente JPEG): ${skipped.join(', ')}</div>`;
            }
            updateUI();
        };

        pickFilesBtn.addEventListener('click', () => imageUploadInput.click());

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
            imageUploadInput.value = '';
        });

        uploadForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (selectedFiles.length === 0 || isUploading) return;

            isUploading = true;
            uploadButton.disabled = true;
            progressWrap.classList.remove('upload-progress-hidden');
            uploadStatus.innerHTML = '';
            const queue = selectedFiles.slice();
            const total = queue.length;
            let ok = 0;
            const failed = [];
            const stillQueued = [];

            for (let i = 0; i < total; i++) {
                const file = queue[i];
                const pct = Math.round((i / total) * 100);
                progressFill.style.width = pct + '%';
                progressLabel.textContent = `Enviando ${i + 1} de ${total}: ${file.name}`;

                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', csrfToken);

                try {
                    const response = await fetch(uploadForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: formData,
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const payload = contentType.includes('application/json')
                        ? await response.json()
                        : null;

                    if (!response.ok) {
                        let msg = 'Falha no envio';
                        if (payload) {
                            if (payload.errors) {
                                const e = payload.errors;
                                msg = Array.isArray(e) ? e.join(' ') : (typeof e === 'object' ? Object.values(e).flat().join(' ') : String(e));
                            } else if (payload.message) {
                                msg = payload.message;
                            }
                        }
                        failed.push({ name: file.name, msg });
                        stillQueued.push(file);
                        continue;
                    }
                    ok++;
                } catch (err) {
                    failed.push({ name: file.name, msg: err.message || 'Erro de rede' });
                    stillQueued.push(file);
                }
            }

            progressFill.style.width = '100%';
            progressLabel.textContent = `Concluído: ${ok} de ${total} enviados.`;

            if (failed.length === 0) {
                uploadStatus.innerHTML = `<div class="bf-alert bf-alert-success">${ok} imagem(ns) enviada(s) e indexada(s).</div>`;
                selectedFiles = [];
            } else {
                selectedFiles = stillQueued;
                const lines = failed.map((f) => `<li class="file-status-err"><strong>${f.name}</strong>: ${f.msg}</li>`).join('');
                uploadStatus.innerHTML = `<div class="bf-alert bf-alert-warning"><p class="mb-2">${ok} enviada(s), ${failed.length} falhou(aram). Ajuste e use <strong>Enviar fila</strong> de novo.</p><ul class="mb-0 ps-3">${lines}</ul></div>`;
            }

            progressWrap.classList.add('upload-progress-hidden');
            progressFill.style.width = '0%';
            progressLabel.textContent = '';

            isUploading = false;
            updateUI();
        });
    });
</script>
@endsection
