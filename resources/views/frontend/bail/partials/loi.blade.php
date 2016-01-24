<?php $chunk = $blocs->chunk(10); ?>
<div class="row">
    @foreach($chunk as $blocs)

        <div class="col-md-6">
            @foreach($blocs as $bloc)
                <div>{!! $bloc->content !!}</div>
            @endforeach
        </div>

    @endforeach
</div>
