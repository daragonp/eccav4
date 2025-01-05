<div class="container">
    <div class="row">
        @if ($worship)
            <div class="col-12 col-md-12 ticker">
                <p class="mb-0">
                    @if (now()->diffInDays($worship->created_at) <= 3)
                        <span class="badge rounded-pill bg-danger">
                            Nuevo
                        </span>
                    @endif
                    Palabra de vida:
                    <span><a href="{{ url('single-feed', $worship->slug) }}">{{ $worship->title }}</a></span>
                </p>
            </div>
        @endif
    </div>
</div>
