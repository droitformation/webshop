@extends('layouts.bail.master')
@section('content')
		      				     
	 <!-- Illustration -->
	 <section id="photo"><img src=" {{ asset('/images/bail/header.jpg') }}" alt=""></section>
	 
	 <div id="content" class="inner">

		 @if(!$page->blocs->isEmpty())
			 <?php $chunk = $page->blocs->chunk(3); ?>
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
		 @endif

	 </div>
@stop
