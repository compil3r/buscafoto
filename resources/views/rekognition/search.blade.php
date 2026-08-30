@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endsection

@section('content')
<div class="page-head">
    <span class="eyebrow">Reconhecimento facial</span>
    <h2><i class="fas fa-search"></i> Buscar por selfie</h2>
    <p>Envie sua selfie para encontrar as fotos em que você aparece.</p>
</div>

<section id="search-section" class="card form-section">
    <p class="mono-tag" style="margin-bottom:20px;">Eventos disponíveis: <strong style="color:var(--blue-mid)">Semana S</strong></p>

    <form id="search-form" enctype="multipart/form-data">
        <label for="selfie-upload" class="dropzone-wrapper">
            <i class="fas fa-camera"></i>
            <span id="file-name-search">Clique aqui ou arraste uma selfie</span>
            <input type="file" id="selfie-upload" name="selfie" accept="image/*" capture="user" hidden>
        </label>

        <div id="image-preview-search" class="image-preview mb-3"></div>

        <button type="submit" class="btn btn-primary btn-block mt-3" id="search-button" style="display: none;">
            <i class="fas fa-search-location"></i> Buscar rosto
        </button>

        <div id="search-status" class="status-message"></div>
    </form>
</section>

<div id="crop-modal" class="modal">
    <div class="crop-modal-panel">
        <div class="crop-modal-head">
            <h3>Ajuste o recorte</h3>
            <button type="button" class="crop-modal-close" id="crop-modal-close" aria-label="Fechar">&times;</button>
        </div>
        <p class="crop-modal-hint">Centralize seu rosto dentro do quadro.</p>
        <div class="crop-modal-body">
            <img id="image-to-crop" src="#" alt="Imagem para recortar">
        </div>
        <div class="crop-modal-actions">
            <button type="button" class="btn btn-outline" id="crop-cancel-btn">Cancelar</button>
            <button type="button" class="btn btn-primary" id="crop-button">
                <i class="fas fa-crop-alt"></i> Confirmar recorte
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selfieUploadInput = document.getElementById("selfie-upload");
    const fileNameSearch = document.getElementById("file-name-search");
    const cropModal = document.getElementById("crop-modal");
    const cropModalClose = document.getElementById("crop-modal-close");
    const cropCancelBtn = document.getElementById("crop-cancel-btn");
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

    const openCropModal = () => {
        cropModal.style.display = "flex";
        document.body.style.overflow = "hidden";
    };

    const closeCropModal = () => {
        cropModal.style.display = "none";
        document.body.style.overflow = "auto";
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    };

    const resetSelection = () => {
        closeCropModal();
        selfieUploadInput.value = "";
        fileNameSearch.textContent = "Clique aqui ou arraste uma selfie";
    };

    selfieUploadInput.addEventListener("change", () => {
        hideStatusMessage(searchStatus);
        const file = selfieUploadInput.files[0];
        if (file) {
            fileNameSearch.textContent = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                imageToCrop.src = e.target.result;
                openCropModal();
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
                imagePreviewSearch.innerHTML = `<img src="${url}" alt="Selfie recortada">`;
                closeCropModal();
                searchButton.style.display = "inline";
            }
        }, "image/jpeg", 0.9);
    });

    cropModalClose.addEventListener("click", resetSelection);
    cropCancelBtn.addEventListener("click", resetSelection);
    cropModal.addEventListener("click", (e) => {
        if (e.target === cropModal) resetSelection();
    });
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && cropModal.style.display === "flex") resetSelection();
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
