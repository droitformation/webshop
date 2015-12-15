<ul class="listFiles">

    @if(!empty($files))
        @foreach($files as $file)
            <li><a class="file-upload-chosen" data-targetid="file" href="{{ $file }}"><img src="{{ asset('pictos/'.$file) }}" alt="" /></a></li>
        @endforeach
    @endif

</ul>