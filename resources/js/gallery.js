document.addEventListener('DOMContentLoaded', () => {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAllBtn = document.getElementById('select-all-btn');
    const downloadSelectedBtn = document.getElementById('download-selected-btn');
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const modalCaption = document.getElementById('modal-caption');
    const modalDownloadBtn = document.getElementById('modal-download-btn');
    const closeModalBtn = modal.querySelector('.close-modal');
    const prevModalBtn = modal.querySelector('.prev-modal');
    const nextModalBtn = modal.querySelector('.next-modal');
    const galleryStatus = document.getElementById('gallery-status');
    const galleryLoading = document.getElementById('gallery-loading-message');

    let currentModalIndex = 0;

    function updateDownloadButton() {
        const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        downloadSelectedBtn.disabled = selectedCount === 0;
        downloadSelectedBtn.innerHTML = selectedCount > 0
            ? `<i class='fas fa-download'></i> Baixar Selecionadas (${selectedCount})`
            : `<i class='fas fa-download'></i> Baixar Selecionadas`;
    }

    function toggleAllCheckboxes() {
        const checkboxes = document.querySelectorAll('.item-checkbox'); // corrigido para a classe correta
        const shouldCheck = Array.from(checkboxes).some(cb => !cb.checked);
    
        checkboxes.forEach(cb => cb.checked = shouldCheck);
    
        console.log("checkboxes atualizados:", [...checkboxes].map(cb => ({value: cb.value, checked: cb.checked})));
        updateDownloadButton();
    }

    function openModal(index) {
        currentModalIndex = index;
        const item = galleryItems[index];
        const img = item.querySelector('img');
        const key = item.getAttribute('data-id');
        console.log("chave", key)
        modalImage.src = img.src;
        modalCaption.textContent = `Imagem ${index + 1} de ${galleryItems.length}`;
        modalDownloadBtn.onclick = () => {
            const link = document.createElement('a');
            link.href = `/download/${encodeURIComponent(key)}`;
            link.download = '';
            console.log("link", link.href)
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function changeModalImage(direction) {
        const newIndex = currentModalIndex + direction;
        if (newIndex >= 0 && newIndex < galleryItems.length) {
            openModal(newIndex);
        }
    }

    selectAllBtn?.addEventListener('click', toggleAllCheckboxes);
    checkboxes.forEach(cb => cb.addEventListener('change', updateDownloadButton));

    galleryItems.forEach((item, index) => {
        const img = item.querySelector('img');
        img.addEventListener('click', () => openModal(index));
    });

    closeModalBtn?.addEventListener('click', closeModal);
    prevModalBtn?.addEventListener('click', () => changeModalImage(-1));
    nextModalBtn?.addEventListener('click', () => changeModalImage(1));
    window.addEventListener('keydown', e => {
        if (modal.style.display === 'block') {
            if (e.key === 'ArrowLeft') changeModalImage(-1);
            if (e.key === 'ArrowRight') changeModalImage(1);
            if (e.key === 'Escape') closeModal();
        }
    });

    downloadSelectedBtn?.addEventListener('click', () => {
        const selectedKeys = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedKeys.length === 0) return;

        if (selectedKeys.length === 1) {
            window.location.href = `/download/${encodeURIComponent(selectedKeys[0])}`;
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/download-multiple';
        form.style.display = 'none';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        selectedKeys.forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'keys[]';
            input.value = key;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
});