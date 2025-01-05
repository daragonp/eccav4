<div>
    @foreach ($opinion as $reg)
        <ul>
            <li><a style="font-weight: bold;" href="{{url('singlefeed', $reg->slug)}}" class="btn btn-outline-primary">{{$reg->title}}</a>
            </li>
        </ul>
    @endforeach
</div>
