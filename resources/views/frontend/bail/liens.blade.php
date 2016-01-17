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

             <div class="row">
                 @foreach($page->blocs as $bloc)
                     <div class="col-md-4"><p><strong>{!! $bloc->title !!}</strong></p></div>
                     <div class="col-md-8">{!! $bloc->content !!}</div>
                 @endforeach
             </div>

         @endif

	 </div>
@stop