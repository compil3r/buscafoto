@extends('layouts.app')

@section('content')
<section id="results-section" class="card w-100">
    <h2 class="mb-3"><i class="fas fa-image-portrait"></i> Resultados da Busca</h2>
    <p class="text-muted">Essas são as fotos mais semelhantes encontradas com base na sua selfie.</p>

    @if(count($matches) > 0)
        <div class="toolbar d-flex justify-content-end gap-2 mb-3">
            <button id="select-all-btn" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-check-double"></i> Selecionar Todas
            </button>
            <button id="download-selected-btn" class="btn btn-sm btn-primary" disabled>
                <i class="fas fa-download"></i> Baixar Selecionadas
            </button>
        </div>

        <div class="gallery-grid" id="gallery">
            @foreach($matches as $match)
            <div class="gallery-item position-relative" data-id="{{ $match['key'] }}">
                <img src="{{ $match['url'] }}" alt="Imagem" class="gallery-image">
                <input type="checkbox" class="item-checkbox" value="{{ $match['key'] }}" >
                <p class="mt-2 small text-center text-muted">
                    Similaridade: {{ number_format($match['similarity'], 1) }}%
                </p>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-meh fa-4x mb-3 text-muted"></i>
            <h4>Nenhum rosto correspondente encontrado</h4>
            <p class="text-muted">Tente outra selfie com melhor iluminação e foco.</p>
            <a href="{{ route('search.form') }}" class="btn btn-secondary mt-2">
                <i class="fas fa-arrow-left"></i> Fazer Nova Busca
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
    <button id="modal-download-btn" class="btn btn-primary btn-small modal-download">
        <i class="fas fa-download"></i> Baixar Imagem
    </button>
</div>

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
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100%;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.85);
    text-align: center;
    padding-top: 60px;
}

.modal-content {
    max-width: 85%;
    max-height: 65vh;
    display: block;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(255,255,255,0.2);
}

#modal-caption {
    margin: 20px 0;
    color: #fff;
    font-size: 18px;
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
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
}
</style>
@endsection

@section('scripts')
@vite('resources/js/gallery.js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAllBtn = document.getElementById('select-all-btn');
    const downloadSelectedBtn = document.getElementById('download-selected-btn');

    selectAllBtn.addEventListener('click', () => {
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        updateDownloadButton();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateDownloadButton));

    function updateDownloadButton() {
        const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        downloadSelectedBtn.disabled = selectedCount === 0;
        downloadSelectedBtn.innerHTML = selectedCount > 0 ?
            `<i class="fas fa-download"></i> Baixar (${selectedCount})` :
            `<i class="fas fa-download"></i> Baixar Selecionadas`;
    }

    downloadSelectedBtn.addEventListener('click', () => {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.closest('.gallery-item').dataset.id);
        if (selected.length === 1) {
            window.location.href = "/download/" + encodeURIComponent(selected[0]);
        } else if (selected.length > 1) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('download.multiple') }}";
            form.style.display = 'none';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);

            selected.forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'keys[]';
                input.value = key;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endsection
