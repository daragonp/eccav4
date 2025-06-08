<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="60000">
    <div class="carousel-inner">
        @foreach($slider as $key => $slide)
        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-6 text-center p-2">
                    <img src="images/slider/{{$slide->image_left}}" class="img-fluid rounded shadow" alt="Izquierda">
                </div>
                <div class="col-md-6 text-center p-2">
                    <img src="images/slider/{{$slide->image_right}}" class="img-fluid rounded shadow" alt="Derecha">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>
