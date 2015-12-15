<ul class="listFiles">

    @if(!empty($files))
        @foreach($files as $file)
            <?php $extension = File::extension($path.$file); ?>
                <li>
                    <a class="file-upload-chosen" data-targetid="file" data-dismiss="modal" href="{{ $path.'/'.$file }}">
                        @if(in_array($extension, $images))
                            <img src="{{ asset($path.'/'.$file) }}" alt="image" />
                        @else
                            {{ $file }}
                        @endif
                    </a>
                </li>
        @endforeach
    @endif

</ul>