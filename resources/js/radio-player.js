document.addEventListener('DOMContentLoaded', () => {
    const radioPlayerContainer = document.getElementById('radio-player-container');
    const radioAudio = document.getElementById('radio-audio');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const playPauseIcon = playPauseBtn.querySelector('i');
    const loadingSpinner = playPauseBtn.querySelector('.loading-spinner');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeToggleBtn = document.getElementById('volume-toggle-btn');
    const volumeSliderPopup = document.querySelector('.volume-slider-popup');
    const volumeIcon = volumeToggleBtn.querySelector('i');
    const infoToggleBtn = document.getElementById('info-toggle-btn');
    const programDetails = document.getElementById('program-details');
  
    // Marca el body para aplicar padding-bottom desde CSS
    document.body.classList.add('has-player');
  
    // --- Reserva dinámica de espacio según la altura real del player ---
    function setPlayerPadding(extra = 8) {
      if (!radioPlayerContainer) return;
      const rect = radioPlayerContainer.getBoundingClientRect();
      const h = Math.ceil(rect.height) + extra; // pequeño colchón
      document.documentElement.style.setProperty('--player-h', `${h}px`);
    }
    // Inicial (tras layout)
    setPlayerPadding();
    // Al terminar de cargar (fuentes/imágenes pueden variar la altura)
    window.addEventListener('load', () => setPlayerPadding());
    // Al redimensionar u orientar
    window.addEventListener('resize', () => setPlayerPadding());
    window.addEventListener('orientationchange', () => setPlayerPadding());
    // Si cambia la transición de details (expand/collapse)
    programDetails?.addEventListener('transitionend', () => setPlayerPadding());
  
    // ---------------------------------------------
    // A partir de aquí: tu lógica de reproducción
    // ---------------------------------------------
  
    // Obtener URLs de datos-atributos
    const defaultPhoto = radioPlayerContainer.dataset.defaultPhoto || '/images/genericprogramimage.png';
    const streamUrl = radioPlayerContainer.dataset.streamUrl || 'https://example.com/default-stream';
    radioAudio.src = streamUrl;
  
    let isPlaying = false;
    playPauseIcon.classList.remove('fa-pause');
    playPauseIcon.classList.add('fa-play');
  
    let isInfoExpanded = localStorage.getItem('infoExpanded') === 'true';
    let isVolumeSliderActive = false;
  
    if (infoToggleBtn && isInfoExpanded) {
      programDetails.classList.add('expanded');
      radioPlayerContainer.classList.add('expanded');
      setPlayerPadding(); // asegura espacio si carga expandido
    }
  
    function notify(msg) {
      if (window.Swal?.fire) {
        Swal.fire({ icon: 'info', text: msg, confirmButtonText: 'OK' });
      } else {
        alert(msg);
      }
    }
  
    function updateVolumeIcon(volume) {
      volumeIcon.classList.remove('fa-volume-up', 'fa-volume-down', 'fa-volume-mute');
      if (volume === 0) {
        volumeIcon.classList.add('fa-volume-mute');
      } else if (volume < 0.5) {
        volumeIcon.classList.add('fa-volume-down');
      } else {
        volumeIcon.classList.add('fa-volume-up');
      }
    }
  
    // Reproducir / Pausar
    playPauseBtn.addEventListener('click', () => {
      if (isPlaying) {
        radioAudio.pause();
      } else {
        radioAudio.play().catch(error => {
          console.error("Error al intentar reproducir el audio:", error);
          notify("Tu navegador bloqueó la reproducción automática. Haz clic en Play para iniciar o ajusta la configuración de tu navegador.");
          playPauseIcon.classList.remove('fa-pause');
          playPauseIcon.classList.add('fa-play');
          isPlaying = false;
        });
      }
    });
  
    radioAudio.addEventListener('pause', () => {
      isPlaying = false;
      playPauseIcon.style.display = 'inline-block';
      loadingSpinner.style.display = 'none';
      playPauseIcon.classList.remove('fa-pause');
      playPauseIcon.classList.add('fa-play');
    });
  
    radioAudio.addEventListener('play', () => {
      isPlaying = true;
      playPauseIcon.style.display = 'inline-block';
      loadingSpinner.style.display = 'none';
      playPauseIcon.classList.remove('fa-play');
      playPauseIcon.classList.add('fa-pause');
    });
  
    // Spinner de buffering
    radioAudio.addEventListener('waiting', () => {
      playPauseIcon.style.display = 'none';
      loadingSpinner.style.display = 'inline-block';
    });
  
    radioAudio.addEventListener('playing', () => {
      playPauseIcon.style.display = 'inline-block';
      loadingSpinner.style.display = 'none';
    });
  
    radioAudio.addEventListener('canplay', () => {
      playPauseIcon.style.display = 'inline-block';
      loadingSpinner.style.display = 'none';
    });
  
    radioAudio.addEventListener('error', (e) => {
      console.error("Error en el audio:", e);
      notify("Hubo un error al cargar el stream de radio. Por favor, inténtalo de nuevo más tarde.");
      isPlaying = false;
      playPauseIcon.style.display = 'inline-block';
      loadingSpinner.style.display = 'none';
      playPauseIcon.classList.remove('fa-pause');
      playPauseIcon.classList.add('fa-play');
    });
  
    // Volumen
    volumeSlider.addEventListener('input', () => {
      radioAudio.volume = volumeSlider.value;
    });
  
    radioAudio.addEventListener('volumechange', () => {
      volumeSlider.value = radioAudio.volume;
      updateVolumeIcon(radioAudio.volume);
    });
  
    volumeToggleBtn.addEventListener('click', (event) => {
      event.stopPropagation();
      isVolumeSliderActive = !isVolumeSliderActive;
      volumeSliderPopup.classList.toggle('active', isVolumeSliderActive);
    });
  
    document.addEventListener('click', (event) => {
      if (isVolumeSliderActive && !volumeSliderPopup.contains(event.target) && !volumeToggleBtn.contains(event.target)) {
        isVolumeSliderActive = false;
        volumeSliderPopup.classList.remove('active');
      }
    });
  
    volumeSlider.value = radioAudio.volume;
    updateVolumeIcon(radioAudio.volume);
  
    // Toggle info (expand/collapse) + recalcular padding
    if (infoToggleBtn) {
      infoToggleBtn.addEventListener('click', () => {
        isInfoExpanded = !isInfoExpanded;
        programDetails.classList.toggle('expanded', isInfoExpanded);
        radioPlayerContainer.classList.toggle('expanded', isInfoExpanded);
        localStorage.setItem('infoExpanded', isInfoExpanded);
        // espera al final de la transición para medir altura final
        setTimeout(setPlayerPadding, 320);
      });
    }
  
    // Actualización de información del programa
    const updateProgramInfo = async () => {
      try {
        const response = await fetch('/api/programa-actual');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
  
        const program = await response.json();
  
        document.getElementById('program-name').textContent = program.nombre_programa || 'Programa Desconocido';
        document.getElementById('director-name').textContent = `Director: ${program.director || 'N/A'}`;
        document.getElementById('director-photo').src = program.foto_director || defaultPhoto;
        document.getElementById('director-photo').alt = `Foto de ${program.director || 'Director'}`;
        document.getElementById('program-schedule').textContent = `Horarios: ${program.horarios_emision ? (Array.isArray(program.horarios_emision) ? program.horarios_emision.join(', ') : program.horarios_emision) : 'N/A'}`;
        document.getElementById('program-description').textContent = program.descripcion_programa || 'Descripción no disponible.';
  
        setPlayerPadding(); // la info nueva puede cambiar la altura
      } catch (error) {
        console.error("Error al obtener la información del programa actual:", error);
        document.getElementById('program-name').textContent = 'Error al cargar programa';
        document.getElementById('director-name').textContent = 'Director: --';
        document.getElementById('program-description').textContent = 'Intenta recargar la página.';
        document.getElementById('program-schedule').textContent = 'Horarios: --';
        document.getElementById('director-photo').src = defaultPhoto;
        setPlayerPadding();
      }
    };
  
    updateProgramInfo();
    setInterval(updateProgramInfo, 5 * 60 * 1000);
  });
  