@isset($quote)
    <div class="container">
        <div class="row">
            @if ($quote->video)
                <div class="col-lg-10 col-md-7 col-12 mb-3 shadow">
                    <img src="{{ asset('images/bible/' . $quote->image) }}"
                        alt="Biblia, sólo biblia; el dedo índice de la mano de Dios" width="100%" height="500px;">
                </div>
                <div class="col-lg-2 col-md-5 col-12 d-flex justify-content-center">
                    <div class="video-circle">
                        <video class="rounded-circle"  onclick="togglePlayPause(this)">
                            <source src="{{ asset('video/quote/' . $quote->video) }}" type="video/mp4">
                            <source src="{{ asset('video/quote/' . $quote->video) }}" type="video/ogg">
                            Tu navegador no soporta el formato de video.
                        </video>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <img src="{{ asset('images/bible/' . $quote->image) }}"
                        alt="Biblia, sólo biblia; el dedo índice de la mano de Dios" width="100%" height="500px;">
                </div>
            @endif
        </div>
    </div>
@else
    <div class="col-12">
        <img src="{{ asset('images/bible/generic.jpeg') }}" alt="Biblia, sólo biblia; el dedo índice de la mano de Dios"
            width="100%" height="500px;">
    </div>
@endisset

<script>
    function togglePlayPause(video) {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    }
</script>
