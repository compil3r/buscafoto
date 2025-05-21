@extends('layouts.app')

@section('content')
<section id="gallery-section" class="card p-4">
    <h2 class="mb-3"><i class="fas fa-images"></i> Galeria de Fotos do Evento</h2>
    <p class="text-muted">Veja todas as fotos enviadas. Clique em uma imagem para ampliar ou selecione várias para baixar.</p>

    @if(count($images) > 0)
    <div class="toolbar d-flex justify-content-end gap-2 mb-3">
        <button id="select-all-btn" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-check-double"></i> Selecionar Todas
        </button>
        <button id="download-selected-btn" class="btn btn-sm btn-primary" disabled>
            <i class="fas fa-download"></i> Baixar Selecionadas
        </button>
    </div>

    <div class="gallery-grid" id="gallery">
        @foreach($images as $image)
        <div class="gallery-item" data-id="{{ $image['key'] }}">
            <img src="{{ $image['url'] }}" alt="Imagem" class="gallery-image">
            <input type="checkbox" class="item-checkbox" value="{{ $image['key'] }}">
            <p class="mt-2 small text-center text-muted">{{ basename($image['key']) }}</p>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-images fa-4x mb-3 text-muted"></i>
        <h4>Nenhuma imagem encontrada</h4>
        <p class="text-muted">Não há imagens disponíveis na galeria.</p>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('upload.form') }}" class="btn btn-primary mt-2">
            <i class="fas fa-upload"></i> Fazer Upload
        </a>
        @endif
    </div>
    @endif
</section>

<!-- Modal Customizado -->
<div id="image-modal" class="modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modal-image">
    <div id="modal-caption"></div>
    <button class="prev-modal">&#10094;</button>
    <button class="next-modal">&#10095;</button>
    <button id="modal-download-btn" class="btn btn-primary btn-small modal-download">
        <i class="fas fa-download"></i> Baixar Imagem
    </button>
</div>

<!-- Status messages -->
<div id="gallery-status" class="status-message"></div>
<div id="gallery-loading-message" class="loading-message" style="display: none;">Carregando galeria...</div>
@endsection

@section('scripts')
@vite('resources/js/gallery.js')
@endsection

@section('styles')
<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
}

.gallery-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    padding: 10px;
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.gallery-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.gallery-image {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 6px;
}

.item-checkbox {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 18px;
    height: 18px;
    accent-color: var(--primary-color);
    cursor: pointer;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.85);
    text-align: center;
    padding-top: 60px;
}

.modal-content {
    margin: auto;
    max-width: 85%;
    max-height: 80vh;
    display: block;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(255,255,255,0.2);
}

#modal-caption {
    color: #ccc;
    margin-top: 10px;
}

.close-modal {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 32px;
    font-weight: bold;
    color: #fff;
    cursor: pointer;
    z-index: 1060;
}

.prev-modal,
.next-modal {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    padding: 10px;
    z-index: 1060;
}

.prev-modal { left: 30px; }
.next-modal { right: 30px; }

.modal-download {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
}
</style>
@endsection
