<?php $chunk = $blocs->chunk(3); ?>
@foreach($chunk as $blocs)

     <div class="row">
         @foreach($blocs as $bloc)
             <div class="col-md-4">
                 <div class="text-center">
                     <p><strong>{!! $bloc->title !!}</strong></p>
                     <p><img src="{{ asset($bloc->image) }}" alt="{!! $bloc->title !!}"></p>
                     {!! $bloc->content !!}
                 </div>
             </div>
         @endforeach
     </div>

@endforeach


