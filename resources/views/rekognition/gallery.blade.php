@extends('layouts.app')

@section('content')
<div class="page-head">
    <span class="eyebrow">Acervo</span>
    <h2><i class="fas fa-images"></i> Galeria do evento</h2>
    <p>Veja todas as fotos enviadas. Clique em uma imagem para ampliar ou selecione várias para baixar.</p>
</div>

<section id="gallery-section" class="card">
    @if(count($images) > 0)
    <div class="toolbar">
        <button id="select-all-btn" class="btn btn-outline btn-sm">
            <i class="fas fa-check-double"></i> Selecionar todas
        </button>
        <button id="download-selected-btn" class="btn btn-primary btn-sm" disabled>
            <i class="fas fa-download"></i> Baixar selecionadas
        </button>
    </div>

    <div class="gallery-grid" id="gallery">
        @foreach($images as $image)
        <div class="gallery-item" data-id="{{ $image['key'] }}">
            <div class="gallery-image-wrap af-frame">
                <img src="{{ $image['url'] }}" alt="Imagem" class="gallery-image">
            </div>
            <input type="checkbox" class="item-checkbox" value="{{ $image['key'] }}">
            <p class="caption">{{ basename($image['key']) }}</p>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-images"></i>
        <h4>Nenhuma imagem encontrada</h4>
        <p>Não há imagens disponíveis na galeria.</p>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('upload.form') }}" class="btn btn-primary mt-2">
            <i class="fas fa-upload"></i> Fazer upload
        </a>
        @endif
    </div>
    @endif
</section>

<div id="image-modal" class="modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modal-image">
    <div id="modal-caption"></div>
    <button class="prev-modal">&#10094;</button>
    <button class="next-modal">&#10095;</button>
    <button id="modal-download-btn" class="btn btn-primary btn-sm modal-download">
        <i class="fas fa-download"></i> Baixar imagem
    </button>
</div>

<div id="gallery-status" class="status-message"></div>
<div id="gallery-loading-message" class="mono-tag" style="display: none;">Carregando galeria...</div>
@endsection

@section('scripts')
@vite('resources/js/gallery.js')
@endsection
