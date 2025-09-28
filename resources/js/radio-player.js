function initRadioPlayer() {
    const root = document.getElementById('radio-player-container')
    if (!root) return
    // Evita re-inicializar si Turbo conserva el nodo
    if (root.dataset.initialized === '1') return
    root.dataset.initialized = '1'
  
    const radioAudio = document.getElementById('radio-audio')
    const playPauseBtn = document.getElementById('play-pause-btn')
    const playPauseIcon = playPauseBtn.querySelector('i')
    const loadingSpinner = playPauseBtn.querySelector('.loading-spinner')
    const volumeSlider = document.getElementById('volume-slider')
    const volumeToggleBtn = document.getElementById('volume-toggle-btn')
    const volumeSliderPopup = root.querySelector('.volume-slider-popup')
    const volumeIcon = volumeToggleBtn.querySelector('i')
    const infoToggleBtn = document.getElementById('info-toggle-btn')
    const programDetails = document.getElementById('program-details')
  
    document.body.classList.add('has-player')
  
    function setPlayerPadding(extra = 8) {
      const rect = root.getBoundingClientRect()
      const h = Math.ceil(rect.height) + extra
      document.documentElement.style.setProperty('--player-h', `${h}px`)
    }
    setPlayerPadding()
    window.addEventListener('load', () => setPlayerPadding(), { once: true })
    window.addEventListener('resize', () => setPlayerPadding())
    window.addEventListener('orientationchange', () => setPlayerPadding())
    programDetails?.addEventListener('transitionend', () => setPlayerPadding())
  
    const defaultPhoto = root.dataset.defaultPhoto || '/images/genericprogramimage.png'
    const streamUrl = root.dataset.streamUrl || 'https://example.com/default-stream'
    radioAudio.src = streamUrl
  
    let isPlaying = false
    playPauseIcon.classList.remove('fa-pause')
    playPauseIcon.classList.add('fa-play')
  
    let isInfoExpanded = localStorage.getItem('infoExpanded') === 'true'
    let isVolumeSliderActive = false
  
    if (infoToggleBtn && isInfoExpanded) {
      programDetails.classList.add('expanded')
      root.classList.add('expanded')
      setPlayerPadding()
    }
  
    function notify(msg) {
      if (window.Swal?.fire) Swal.fire({ icon: 'info', text: msg, confirmButtonText: 'OK' })
      else alert(msg)
    }
  
    function updateVolumeIcon(volume) {
      volumeIcon.classList.remove('fa-volume-up', 'fa-volume-down', 'fa-volume-mute')
      if (volume === 0) volumeIcon.classList.add('fa-volume-mute')
      else if (volume < 0.5) volumeIcon.classList.add('fa-volume-down')
      else volumeIcon.classList.add('fa-volume-up')
    }
  
    playPauseBtn.addEventListener('click', () => {
      if (isPlaying) {
        radioAudio.pause()
      } else {
        radioAudio.play().catch(err => {
          console.error('Autoplay error:', err)
          notify('Tu navegador bloqueó la reproducción automática. Haz clic en Play para iniciar o ajusta la configuración.')
          playPauseIcon.classList.remove('fa-pause')
          playPauseIcon.classList.add('fa-play')
          isPlaying = false
        })
      }
    })
  
    radioAudio.addEventListener('pause', () => {
      isPlaying = false
      playPauseIcon.style.display = 'inline-block'
      loadingSpinner.style.display = 'none'
      playPauseIcon.classList.remove('fa-pause')
      playPauseIcon.classList.add('fa-play')
    })
    radioAudio.addEventListener('play', () => {
      isPlaying = true
      playPauseIcon.style.display = 'inline-block'
      loadingSpinner.style.display = 'none'
      playPauseIcon.classList.remove('fa-play')
      playPauseIcon.classList.add('fa-pause')
    })
    radioAudio.addEventListener('waiting', () => {
      playPauseIcon.style.display = 'none'
      loadingSpinner.style.display = 'inline-block'
    })
    radioAudio.addEventListener('playing', () => {
      playPauseIcon.style.display = 'inline-block'
      loadingSpinner.style.display = 'none'
    })
    radioAudio.addEventListener('canplay', () => {
      playPauseIcon.style.display = 'inline-block'
      loadingSpinner.style.display = 'none'
    })
    radioAudio.addEventListener('error', (e) => {
      console.error('Stream error:', e)
      notify('Hubo un error al cargar el stream de radio. Inténtalo más tarde.')
      isPlaying = false
      playPauseIcon.style.display = 'inline-block'
      loadingSpinner.style.display = 'none'
      playPauseIcon.classList.remove('fa-pause')
      playPauseIcon.classList.add('fa-play')
    })
  
    volumeSlider.addEventListener('input', () => {
      radioAudio.volume = volumeSlider.value
    })
    radioAudio.addEventListener('volumechange', () => {
      volumeSlider.value = radioAudio.volume
      updateVolumeIcon(radioAudio.volume)
    })
    volumeToggleBtn.addEventListener('click', (ev) => {
      ev.stopPropagation()
      isVolumeSliderActive = !isVolumeSliderActive
      volumeSliderPopup.classList.toggle('active', isVolumeSliderActive)
    })
    document.addEventListener('click', (ev) => {
      if (isVolumeSliderActive && !volumeSliderPopup.contains(ev.target) && !volumeToggleBtn.contains(ev.target)) {
        isVolumeSliderActive = false
        volumeSliderPopup.classList.remove('active')
      }
    })
    volumeSlider.value = radioAudio.volume
    updateVolumeIcon(radioAudio.volume)
  
    if (infoToggleBtn) {
      infoToggleBtn.addEventListener('click', () => {
        isInfoExpanded = !isInfoExpanded
        programDetails.classList.toggle('expanded', isInfoExpanded)
        root.classList.toggle('expanded', isInfoExpanded)
        localStorage.setItem('infoExpanded', isInfoExpanded)
        setTimeout(setPlayerPadding, 320)
      })
    }
  
    async function updateProgramInfo() {
      try {
        const resp = await fetch('/api/programa-actual')
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`)
        const program = await resp.json()
        document.getElementById('program-name').textContent = program.nombre_programa || 'Programa Desconocido'
        document.getElementById('director-name').textContent = `Director: ${program.director || 'N/A'}`
        document.getElementById('director-photo').src = program.foto_director || defaultPhoto
        document.getElementById('director-photo').alt = `Foto de ${program.director || 'Director'}`
        document.getElementById('program-schedule').textContent =
          `Horarios: ${program.horarios_emision ? (Array.isArray(program.horarios_emision) ? program.horarios_emision.join(', ') : program.horarios_emision) : 'N/A'}`
        document.getElementById('program-description').textContent = program.descripcion_programa || 'Descripción no disponible.'
        setPlayerPadding()
      } catch (e) {
        console.error('Program info error:', e)
        document.getElementById('program-name').textContent = 'Error al cargar programa'
        document.getElementById('director-name').textContent = 'Director: --'
        document.getElementById('program-description').textContent = 'Intenta recargar la página.'
        document.getElementById('program-schedule').textContent = 'Horarios: --'
        document.getElementById('director-photo').src = defaultPhoto
        setPlayerPadding()
      }
    }
    updateProgramInfo()
    setInterval(updateProgramInfo, 5 * 60 * 1000)
  }
  
  // Inicializa en primer load normal…
  document.addEventListener('DOMContentLoaded', initRadioPlayer)
  // …y también en cada navegación Turbo (sin full reload)
  document.addEventListener('turbo:load', initRadioPlayer)
  