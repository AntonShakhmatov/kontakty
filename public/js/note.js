function showModal(note) {
    const modal = document.querySelector('.modal');
    const content = document.querySelector('.modal-content');
    const closeButton = document.createElement('span');
    closeButton.classList.add('close-button');
    closeButton.innerHTML = 'Close';
    content.innerHTML = note;
    content.appendChild(closeButton);
    modal.style.display = 'flex';

    modal.addEventListener('click', function(event) {
        if (event.target === modal || event.target === closeButton) {
            modal.style.display = 'none';
        }
    });

    document.addEventListener('keyup', function(event) {
        if (event.key === 'Escape') {
            modal.style.display = 'none';
        }
    });
}