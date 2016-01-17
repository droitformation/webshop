@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->

	 <div id="content" class="inner">
	 	 <div class="row">
	 	 	<div class="col-md-12">
                <h3 class="line up">{{ $page->title }}</h3>
	 	 	</div>
	 	 </div>

		 @if(!$page->blocs->isEmpty())
			 <?php $chunk = $page->blocs->chunk(10); ?>
                 <div class="row">
                 @foreach($chunk as $blocs)

                     <div class="col-md-6">
                         @foreach($blocs as $bloc)
                             <div>{!! $bloc->content !!}</div>
                         @endforeach
                    </div>

                 @endforeach
             </div>
		 @endif

	 </div>
@stop
