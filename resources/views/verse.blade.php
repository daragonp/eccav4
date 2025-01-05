<style>
    .containerim {
        position: relative;
        display: inline-block;
    }

    .texto-superpuesto {
        width: 80%;
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: #ffffff;
        padding: 10px;
    }

    .texto-superpuesto h2,
    .texto-superpuesto p {
        margin: 0;
    }

    .texto-superpuesto h2 {
        font-size: 20px;
        color: white;
    }

    .texto-superpuesto p {
        font-size: 22px;
        color: white;
    }
</style>
<div class="container">
    <div class="row">
        <div class="containerim">
            @if (!$verses || !$verses->image)
                <img src="images/bible/generic.png" height="600px" width="100%" class="im" alt="Imagen">
                <div class="texto-superpuesto">
                    <h2>Emancipación Cristiana Afro</h2>
                    <p>Palabra de vida, para el pueblo de tez brillante. <br> Biblia, solo biblia.</p>
                </div>
            @else
                <img src="images/bible/{{ $verses->image }}" height="600px" width="100%" class="im" alt="Imagen">
                <{{-- div class="texto-superpuesto">
                    <h2>{{ $verses->text }} </h2>
                    <p>{{ $verses->book }} {{ $verses->chapter }}:{{ $verses->cite }}</p>
                </div> --}}
            @endif
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        var imagen = document.querySelector('.containerim im');

        imagen.style.filter = 'brightness(70%)';
    });
</script>
<br>
