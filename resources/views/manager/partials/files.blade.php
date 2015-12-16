<ul id="gallery">
    @if(!empty($files))
        @foreach($files as $file)
            <?php $extension = File::extension($path.$file); ?>
                <li class="file-item">
                    <a class="file-upload-chosen" title="{{ $file }}" data-targetid="file" data-dismiss="modal" href="{{ $path.'/'.$file }}">
                        @if(in_array($extension, $images))
                            <img src="{{ asset($path.'/'.$file) }}" alt="image" />
                        @else
                            <img style="padding: 5px;" src="{{ asset('images/text.svg') }}" alt="image" />
                        @endif
                    </a>
                    <?php $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file); ?>
                    <p>{{ $withoutExt }}</p>
                </li>
        @endforeach
    @endif
</ul>

