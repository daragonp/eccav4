<div class="row">
    <div class="col-lg-8 offset-lg-2 text-center">
        <div class="section-title">
            <h3>Programas</h3>
        </div>
    </div>
</div>
<hr>
<div class="container">
    <audio id="audio" preload="metadata" tabindex="0" controls="controls" controlsList="nodownload">
        <source src="">
    </audio>

    <ul id="playlist">
        @foreach($podcast as $pod)
        <li class="active text-success">
            <a href="/audio/podcast/{{$pod->audio_file}}">
                <i class="far fa-play-circle"></i>{{$pod->id}} {{$pod->title}}
            </a>
        </li>
        @endforeach
    </ul>
</div>