<div id="radio-player-container" class="radio-player-container"
    data-default-photo="{{ asset('images/genericprogramimage.png') }}"
    data-stream-url="{{ env('RADIO_STREAM_URL', 'https://example.com/default-stream') }}">
    <div class="player-controls">
        <audio id="radio-audio" preload="none"></audio>

        <div class="station-branding">
            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo de la Emisora" class="station-logo">
            <span class="station-name">Radio Emancipación Cristiana Afro</span>
            <span class="live-indicator">EN VIVO</span>
        </div>

        <div class="player-buttons-group">
            <div class="volume-group">
                <button id="volume-toggle-btn" class="player-button" title="Volumen">
                    <i class="fas fa-volume-up"></i>
                </button>
                <div class="volume-slider-popup">
                    <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="0.7"
                        class="volume-slider">
                </div>
            </div>

            <button id="play-pause-btn" class="player-button" title="Reproducir/Pausar">
                <i class="fas fa-play"></i>
                <span class="loading-spinner" style="display: none;"></span> </button>

            <button id="info-toggle-btn" class="player-button toggle-arrow" title="Información">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
    </div>

    <div id="program-details" class="program-details">
        <div class="program-grid">
            <div class="program-column director-photo-column">
                <img id="director-photo" src="{{ asset('images/logo/ImagenRadio.jpg') }}" alt="Foto del Director">
            </div>
            <div class="program-column info-column">
                <h3 id="program-name">Cargando programa...</h3>
                <p id="program-description">Descripción: N/A</p>
                <p id="director-name">Director: N/A</p>
            </div>
            <div class="program-column schedule-column">
                <i class="fas fa-clock icono-horario"></i>
                <p id="program-schedule">Horarios: N/A</p>
            </div>
        </div>
    </div>
</div>
