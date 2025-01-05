<div class="center">
    <h3>Mensaje de la semana</h3>
    @foreach ($news as $item)
        <article class="cta center">
            @if ($item->image)
                <img class="imgcard" src='{{ asset('images/news/' . $item->image) }}' alt='ECCA'>
            @else
                <img class="imgcard" src={{ asset('images/news/generic.jpg') }}' alt='ECCA'>
            @endif
                <div class="cta__text-column">
                    <h5 class="imgh5">{{ $item->title }}</h5><br>
                    <p class="abstract">{{ $item->abstract }}</p>
                    <a href="{{ url('showpost', $item->slug) }}">Leer más</a>
                </div>
        </article>
    @endforeach
</div>
