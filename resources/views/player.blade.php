<div id="radio-player-container" class="radio-player-container">
    <div class="player-controls">
        <audio id="radio-audio" preload="none"></audio>

        {{-- Contenedor del Logo y Nombre de la Emisora --}}
        <div class="station-branding">
            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo de la Emisora" class="station-logo">
            <span class="station-name">Radio Emancipación Cristiana Afro</span>
        </div>
        
        <span class="live-indicator">EN VIVO</span>

        {{-- Controles de volumen (MOVIDO ANTES DEL PLAY) --}}
        <div class="volume-group">
            <button id="volume-toggle-btn" class="player-button">
                <i class="fas fa-volume-up"></i>
            </button>
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="0.7">
        </div>

        {{-- Botón de Play/Pause (MOVIDO DESPUÉS DEL VOLUMEN) --}}
        <button id="play-pause-btn" class="player-button">
            <i class="fas fa-play"></i>
        </button>
        
        <div class="program-info-toggle">
            <button id="info-toggle-btn" class="player-button toggle-arrow">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
    </div>

    <div id="program-details" class="program-details">
        <h3 id="program-name">Cargando programa...</h3>
        <div class="director-info">
            <img id="director-photo" src="{{ asset('images/logo/ImagenRadio.jpg') }}" alt="Foto del Director">
            <p id="director-name">Director: N/A</p>
        </div>
        <p id="program-schedule">Horarios: N/A</p>
        <p id="program-description">Descripción del programa: N/A</p>
    </div>
</div>