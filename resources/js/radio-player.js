document.addEventListener('DOMContentLoaded', () => {
  const radioPlayerContainer = document.getElementById('radio-player-container');
  const radioAudio = document.getElementById('radio-audio');
  const playPauseBtn = document.getElementById('play-pause-btn');
  const playPauseIcon = playPauseBtn.querySelector('i');
  const loadingSpinner = playPauseBtn.querySelector('.loading-spinner'); // Nuevo spinner
  const volumeSlider = document.getElementById('volume-slider');
  const volumeToggleBtn = document.getElementById('volume-toggle-btn');
  const volumeSliderPopup = document.querySelector('.volume-slider-popup');
  const volumeIcon = volumeToggleBtn.querySelector('i'); // Nuevo: icono de volumen
  const infoToggleBtn = document.getElementById('info-toggle-btn');
  const programDetails = document.getElementById('program-details');

  // Obtener URLs de datos-atributos para mayor flexibilidad
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
  }

  // Función para actualizar el icono de volumen
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

  // Event Listeners para el control de reproducción
  playPauseBtn.addEventListener('click', () => {
    if (isPlaying) {
      radioAudio.pause();
    } else {
      // Intenta reproducir, capturando posibles errores de autoplay
      radioAudio.play().catch(error => {
        console.error("Error al intentar reproducir el audio:", error);
        alert("Tu navegador bloqueó la reproducción automática. Haz clic en Play para iniciar o ajusta la configuración de tu navegador.");
        // Asegúrate de que el icono vuelva a 'play' si falla la reproducción
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
        isPlaying = false;
      });
    }
  });

  radioAudio.addEventListener('pause', () => {
    isPlaying = false;
    playPauseIcon.style.display = 'inline-block'; // Muestra el icono
    loadingSpinner.style.display = 'none'; // Oculta el spinner
    playPauseIcon.classList.remove('fa-pause');
    playPauseIcon.classList.add('fa-play');
  });

  radioAudio.addEventListener('play', () => {
    isPlaying = true;
    playPauseIcon.style.display = 'inline-block'; // Muestra el icono
    loadingSpinner.style.display = 'none'; // Oculta el spinner
    playPauseIcon.classList.remove('fa-play');
    playPauseIcon.classList.add('fa-pause');
  });

  // Eventos para el spinner de carga
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

  // Manejo de errores de carga del stream
  radioAudio.addEventListener('error', (e) => {
    console.error("Error en el audio:", e);
    alert("Hubo un error al cargar el stream de radio. Por favor, inténtalo de nuevo más tarde.");
    isPlaying = false;
    playPauseIcon.style.display = 'inline-block';
    loadingSpinner.style.display = 'none';
    playPauseIcon.classList.remove('fa-pause');
    playPauseIcon.classList.add('fa-play');
  });

  // Control de volumen
  volumeSlider.addEventListener('input', () => {
    radioAudio.volume = volumeSlider.value;
  });

  radioAudio.addEventListener('volumechange', () => { // Actualiza slider e icono si el volumen cambia por otros medios
    volumeSlider.value = radioAudio.volume;
    updateVolumeIcon(radioAudio.volume);
  });

  volumeToggleBtn.addEventListener('click', (event) => {
    event.stopPropagation();
    isVolumeSliderActive = !isVolumeSliderActive;
    volumeSliderPopup.classList.toggle('active', isVolumeSliderActive);
  });

  // Cierra el slider de volumen si se hace clic fuera
  document.addEventListener('click', (event) => {
    if (isVolumeSliderActive && !volumeSliderPopup.contains(event.target) && !volumeToggleBtn.contains(event.target)) {
      isVolumeSliderActive = false;
      volumeSliderPopup.classList.remove('active');
    }
  });

  // Inicializa el slider y el icono de volumen
  volumeSlider.value = radioAudio.volume;
  updateVolumeIcon(radioAudio.volume);

  // Toggle de información
  if (infoToggleBtn) {
    infoToggleBtn.addEventListener('click', () => {
      isInfoExpanded = !isInfoExpanded;
      programDetails.classList.toggle('expanded', isInfoExpanded);
      radioPlayerContainer.classList.toggle('expanded', isInfoExpanded);
      localStorage.setItem('infoExpanded', isInfoExpanded);
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

    } catch (error) {
      console.error("Error al obtener la información del programa actual:", error);
      // Opcional: Mostrar un mensaje de error en el reproductor si la API falla
      document.getElementById('program-name').textContent = 'Error al cargar programa';
      document.getElementById('director-name').textContent = 'Director: --';
      document.getElementById('program-description').textContent = 'Intenta recargar la página.';
      document.getElementById('program-schedule').textContent = 'Horarios: --';
      document.getElementById('director-photo').src = defaultPhoto; // Vuelve a la foto por defecto
    }
  };

  // Llama a la función al cargar y luego cada 5 minutos
  updateProgramInfo();
  setInterval(updateProgramInfo, 5 * 60 * 1000); // 5 minutos
});