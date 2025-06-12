document.addEventListener('DOMContentLoaded', () => {
    const radioPlayerContainer = document.getElementById('radio-player-container');
    const radioAudio = document.getElementById('radio-audio');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const playPauseIcon = playPauseBtn.querySelector('i');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeToggleBtn = document.getElementById('volume-toggle-btn'); // ¡Nuevo!
    const volumeSliderPopup = document.querySelector('.volume-slider-popup'); // ¡Nuevo!
    const infoToggleBtn = document.getElementById('info-toggle-btn');
    const programDetails = document.getElementById('program-details');

    const defaultPhoto = typeof defaultDirectorPhotoUrl !== 'undefined' ? defaultDirectorPhotoUrl : '/images/genericprogramimage.png';
    const streamUrl = typeof radioStreamUrl !== 'undefined' ? radioStreamUrl : 'https://example.com/default-stream';

    radioAudio.src = streamUrl;

    let isPlaying = false;
    playPauseIcon.classList.remove('fa-pause');
    playPauseIcon.classList.add('fa-play');

    let isInfoExpanded = false;
    let isVolumeSliderActive = false; // ¡Nuevo! Estado del slider de volumen


    // 1. Control de Play/Pause (sin cambios)
    playPauseBtn.addEventListener('click', () => {
        if (isPlaying) {
            radioAudio.pause();
            playPauseIcon.classList.remove('fa-pause');
            playPauseIcon.classList.add('fa-play');
        } else {
            radioAudio.play()
                .then(() => {
                    isPlaying = true;
                    playPauseIcon.classList.remove('fa-play');
                    playPauseIcon.classList.add('fa-pause');
                })
                .catch(error => {
                    console.error("Error al intentar reproducir el audio:", error);
                    alert("¡Ups! Parece que tu navegador bloqueó la reproducción automática. Haz clic en el botón de Play para iniciar la radio.");
                    isPlaying = false;
                    playPauseIcon.classList.remove('fa-pause');
                    playPauseIcon.classList.add('fa-play');
                });
        }
    });

    radioAudio.addEventListener('pause', () => {
        isPlaying = false;
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
    });

    radioAudio.addEventListener('play', () => {
        isPlaying = true;
        playPauseIcon.classList.remove('fa-play');
        playPauseIcon.classList.add('fa-pause');
    });

    // 2. Control de Volumen
    volumeSlider.addEventListener('input', () => {
        radioAudio.volume = volumeSlider.value;
    });

    // ¡NUEVA LÓGICA DE TOGGLE PARA EL SLIDER DE VOLUMEN!
    volumeToggleBtn.addEventListener('click', (event) => {
        event.stopPropagation(); // Evitar que el clic se propague al documento
        isVolumeSliderActive = !isVolumeSliderActive;
        volumeSliderPopup.classList.toggle('active', isVolumeSliderActive);
    });

    // Cierra el slider de volumen si el usuario hace clic fuera de él
    document.addEventListener('click', (event) => {
        if (isVolumeSliderActive && !volumeSliderPopup.contains(event.target) && !volumeToggleBtn.contains(event.target)) {
            isVolumeSliderActive = false;
            volumeSliderPopup.classList.remove('active');
        }
    });

    // Inicializar el slider con el volumen actual del audio (por defecto 0.7)
    volumeSlider.value = radioAudio.volume;

    // 3. Despliegue de Información del Programa (sin cambios)
    infoToggleBtn.addEventListener('click', () => {
        isInfoExpanded = !isInfoExpanded;
        programDetails.classList.toggle('expanded', isInfoExpanded);
        radioPlayerContainer.classList.toggle('expanded', isInfoExpanded);
    });

    // 4. Actualización de Información del Programa (sin cambios)
    const updateProgramInfo = async () => {
        try {
            const response = await fetch('/api/programa-actual');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const program = await response.json();

            document.getElementById('program-name').textContent = program.nombre_programa;
            document.getElementById('director-name').textContent = `Director: ${program.director}`;
            document.getElementById('director-photo').src = program.foto_director || defaultPhoto;
            document.getElementById('director-photo').alt = `Foto de ${program.director}`;
            document.getElementById('program-schedule').textContent = `Horarios: ${program.horarios_emision ? (Array.isArray(program.horarios_emision) ? program.horarios_emision.join(', ') : program.horarios_emision) : 'N/A'}`;
            document.getElementById('program-description').textContent = program.descripcion_programa || 'Descripción no disponible.';

        } catch (error) {
            console.error("Error al obtener la información del programa actual:", error);
        }
    };

    updateProgramInfo();
    setInterval(updateProgramInfo, 5 * 60 * 1000);
});