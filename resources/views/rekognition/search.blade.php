@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    #search-section {
        max-width: 600px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .dropzone-wrapper {
        border: 2px dashed var(--primary-color);
        padding: 30px;
        text-align: center;
        border-radius: 12px;
        background-color: var(--light-gray);
        cursor: pointer;
        position: relative;
        transition: background 0.3s;
        display: block;
    }
    .dropzone-wrapper:hover {
        background-color: #eaf1f8;
    }
    .dropzone-wrapper i {
        font-size: 3rem;
        color: var(--primary-color);
    }
    .dropzone-wrapper span {
        display: block;
        color: var(--dark-gray);
        font-weight: 500;
        margin-top: 10px;
    }
    #selfie-upload {
        display: none;
    }
    .status-message {
        padding: 10px;
        border-radius: 6px;
        display: none;
        margin-top: 15px;
    }
    .status-message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        display: block;
    }
    .status-message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        display: block;
    }
    .status-message.loading {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        display: block;
    }
    #search-form {
        text-align: center;
    }
    .image-preview img {
        width: 60%;
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<section id="search-section">
    <h2 class="mb-3"><i class="fas fa-search"></i> Buscar por Selfie</h2>
    <p class="text-muted">Envie sua selfie para encontrar fotos correspondentes em eventos.</p>
    {{-- Eventos disponiveis FBV quarta-feira, upload completo. FBV quinta-feira, upload completo. FBV sexta-feira, upload em andamento --}}
    <p class="text-muted">Eventos disponíveis: 
        <ul>
        <li><strong>FBV Quarta-feira ✅</strong></li>
        <li><strong>FBV Quinta-feira ✅</strong></li>
        <li><strong>FBV Sexta-feira ⏳</strong></li>
    </ul></p>

    <form id="search-form" enctype="multipart/form-data">
        <label for="selfie-upload" class="dropzone-wrapper">
            <i class="fas fa-camera"></i>
            <span id="file-name-search">Clique aqui ou arraste uma selfie</span>
            <input type="file" id="selfie-upload" name="selfie" accept="image/*" capture="user">
        </label>

        <div id="cropper-container" class="mt-3" style="display: none;">
            <p class="mb-2">Ajuste o recorte para focar no rosto:</p>
            <div class="border rounded p-2 text-center bg-light">
                <img id="image-to-crop" src="#" alt="Imagem para recortar" style="max-width: 100%; border-radius: 8px;">
            </div>
            <button type="button" id="crop-button" class="btn btn-primary mt-3">
                <i class="fas fa-crop-alt"></i> Confirmar Recorte
            </button>
        </div>

        <div id="image-preview-search" class="image-preview text-center mb-3"></div>

        <button type="submit" class="btn btn-secondary mt-3" id="search-button" style="display: none;">
            <i class="fas fa-search-location"></i> Buscar Rosto
        </button>

        <div id="search-status" class="status-message"></div>
    </form>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selfieUploadInput = document.getElementById("selfie-upload");
    const fileNameSearch = document.getElementById("file-name-search");
    const cropperContainer = document.getElementById("cropper-container");
    const imageToCrop = document.getElementById("image-to-crop");
    const cropButton = document.getElementById("crop-button");
    const imagePreviewSearch = document.getElementById("image-preview-search");
    const searchButton = document.getElementById("search-button");
    const searchStatus = document.getElementById("search-status");
    const searchForm = document.getElementById("search-form");
    let cropper = null;
    let croppedImageData = null;

    const showStatusMessage = (el, message, type = 'info') => {
        el.className = `status-message ${type}`;
        el.textContent = message;
        el.style.display = 'block';
    };

    const hideStatusMessage = (el) => {
        el.textContent = '';
        el.style.display = 'none';
    };

    selfieUploadInput.addEventListener("change", () => {
        hideStatusMessage(searchStatus);
        const file = selfieUploadInput.files[0];
        if (file) {
            fileNameSearch.textContent = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                imageToCrop.src = e.target.result;
                cropperContainer.style.display = "block";
                if (cropper) cropper.destroy();
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 1,
                    background: false,
                    autoCropArea: 0.8,
                    responsive: true
                });
            };
            reader.readAsDataURL(file);
        }
    });

    cropButton.addEventListener("click", () => {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob((blob) => {
            if (blob) {
                croppedImageData = blob;
                const url = URL.createObjectURL(blob);
                imagePreviewSearch.innerHTML = `<img src="${url}" alt="Cropped Selfie">`;
                cropperContainer.style.display = "none";
                searchButton.style.display = "inline";
            }
        }, "image/jpeg", 0.9);
    });

    searchForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        if (!croppedImageData) {
            showStatusMessage(searchStatus, "Selecione uma selfie e confirme o recorte.", "error");
            return;
        }

        const formData = new FormData();
        formData.append("selfie", croppedImageData, "selfie.jpg");
        showStatusMessage(searchStatus, "Buscando rosto...", "loading");
        searchButton.disabled = true;

        try {
            const response = await fetch("{{ route('search.submit') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            const data = await response.text();
            document.open();
            document.write(data);
            document.close();
        } catch (error) {
            showStatusMessage(searchStatus, "Erro: " + error.message, "error");
        } finally {
            searchButton.disabled = false;
        }
    });
});
</script>
@endsection
