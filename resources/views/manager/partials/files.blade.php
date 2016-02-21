<ul id="gallery">
    @if(!empty($files))
        @foreach($files as $file)
            @if(!is_array($file))
                <?php $extension = File::extension($path.$file); ?>
                <li class="file-item">
                    <button data-src="{{ $path.'/'.$file }}" data-action="Fichier" data-what="Supprimer" class="btn btn-xs btn-danger file-manager-delete">x</button>
                    {{--  <button data-src="{{ $path.'/'.$file }}" class="btn btn-xs btn-info file-manager-crop">Crop</button>--}}
                    <a class="file-upload-chosen" title="{{ $file }}" data-targetid="image" data-dismiss="modal" href="{{ $path.'/'.$file }}">
                        @if(in_array($extension, $images))
                            @if(File::exists(public_path($path.'/thumbs/'.$file)))
                                <img src="{{ asset($path.'/thumbs/'.$file) }}" alt="image" />
                            @else
                                <img src="{{ asset($path.'/'.$file) }}" alt="image" />
                            @endif
                        @else
                            <img style="padding: 5px;" src="{{ asset('images/text.svg') }}" alt="Document" />
                        @endif
                    </a>
                    <?php $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file); ?>
                    <p>{{ $withoutExt }}</p>
                </li>
            @else
                <li>Aucun fichier à ce niveau</li>
            @endif
        @endforeach
    @else
        <li>Aucun fichier à ce niveau</li>
    @endif
</ul>

