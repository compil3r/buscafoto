@extends('layouts.app')

@section('content')
<div class="page-head">
    <span class="eyebrow">Resultado da busca</span>
    <h2><i class="fas fa-image-portrait"></i> Suas fotos encontradas</h2>
    <p>Essas são as fotos mais semelhantes encontradas com base na sua selfie.</p>
</div>

<section id="results-section" class="card w-100">
    @if(count($matches) > 0)
        <div class="toolbar">
            <button id="select-all-btn" class="btn btn-outline btn-sm">
                <i class="fas fa-check-double"></i> Selecionar todas
            </button>
            <button id="download-selected-btn" class="btn btn-primary btn-sm" disabled>
                <i class="fas fa-download"></i> Baixar selecionadas
            </button>
        </div>

        <div class="gallery-grid" id="gallery">
            @foreach($matches as $match)
            <div class="gallery-item" data-id="{{ $match['key'] }}">
                <div class="gallery-image-wrap af-frame">
                    <img src="{{ $match['url'] }}" alt="Imagem" class="gallery-image">
                </div>
                <input type="checkbox" class="item-checkbox" value="{{ $match['key'] }}">
                <div class="match-row">
                    <span class="similarity-pill"><i class="fas fa-check"></i> {{ number_format($match['similarity'], 0) }}% de semelhança</span>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-meh"></i>
            <h4>Nenhum rosto correspondente encontrado</h4>
            <p>Tente outra selfie com melhor iluminação e foco.</p>
            <a href="{{ route('search.form') }}" class="btn btn-primary mt-2">
                <i class="fas fa-arrow-left"></i> Fazer nova busca
            </a>
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

@endsection

@section('scripts')
@vite('resources/js/gallery.js')
@endsection
