<?php $chunk = $contents->chunk(10); ?>
<div class="row">
    @foreach($chunk as $blocs)

        <div class="col-md-6">
            @foreach($blocs as $bloc)
                <div class="text-justify">{!! $bloc->content !!}</div>
            @endforeach
        </div>

    @endforeach
</div>
