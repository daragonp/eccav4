<section class="daily-container">
    @if ($quote)
        <div class="daily-word-section">
        <div class="left-block">
          <h2 class="title">PALABRA DE VIDA<br>DEL DÍA</h2>
          <a href="{{ asset('documents/quote/' . $quote->video) }}" download="{{ asset('documents/quote/' . $quote->video) }}" class="download-button">
            Descarga PDF aquí
            <span class="circle-badge">NEW</span>
          </a>
        </div>
        <div class="right-block">
            <img src="{{ asset('images/bible/' . $quote->image) }}" alt="Imagen del día" />
        </div>
      </div>
    @endif
  </section>  