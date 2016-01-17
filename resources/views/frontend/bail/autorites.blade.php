@extends('layouts.bail.master')
@section('content')

	 <div id="content" class="inner">
	 	 <div class="row">
	 	 	<div class="col-md-12">
                <h3 class="line up">{{ $page->title }}</h3>
                {!! $page->content !!}
	 	 	</div>
	 	 </div>

         @if(!$page->blocs->isEmpty())
             <?php $chunk = $page->blocs->chunk(3); ?>
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
         @endif

	 </div>
@stop