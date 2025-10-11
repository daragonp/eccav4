document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la funcionalidad de los modales
    const modals = document.querySelectorAll('.tw-modal');
    
    modals.forEach(modal => {
        // Asegurar que el modal tenga el tamaño adecuado al abrirse
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.closest('[data-modal-close]')) {
                modal.classList.remove('open');
                document.getElementById('backdrop')?.classList.remove('open');
            }
        });
    });
    
    // Mejorar la experiencia con los campos de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
            const label = this.nextElementSibling;
            if (label && label.classList.contains('file-input-label')) {
                label.textContent = fileName;
            }
        });
    });
    
    // Función para previsualizar imágenes
    window.previewImage = function(url) {
        const modal = document.getElementById('imagePreviewModal');
        const img = document.getElementById('previewImage');
        
        if (modal && img) {
            img.src = url;
            modal.classList.add('open');
            document.getElementById('backdrop')?.classList.add('open');
        }
    };
    
    // Mejorar la accesibilidad de los modales
    const openButtons = document.querySelectorAll('[data-modal-open]');
    
    openButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-open');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                // Mover el modal al final del body para asegurar que esté por encima de todo
                document.body.appendChild(modal);
                
                // Asegurar que el backdrop esté visible
                document.getElementById('backdrop')?.classList.add('open');
                
                // Añadir clase para mostrar el modal
                modal.classList.add('open');
                
                // Enfocar el primer elemento del formulario
                const firstInput = modal.querySelector('input, textarea, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });
    });
});