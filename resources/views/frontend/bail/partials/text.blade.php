<?php $chunk = $contents->chunk(3); ?>
@foreach($chunk as $blocs)

    <div class="row">
        @foreach($blocs as $bloc)
            <div class="col-md-4 homepageBlock">
                <div class="{{ !empty($bloc->style) ? $bloc->style : '' }}">
                    <h5 class="line">{!! $bloc->title !!}</h5>
                    {!! $bloc->content !!}
                </div>
            </div>
        @endforeach
    </div>

@endforeach
