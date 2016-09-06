@extends('frontend.bail.layouts.master')
@section('content')
		      				     
	 <!-- Illustration -->
	 <section id="photo"><img src=" {{ asset('/images/bail/header.jpg') }}" alt=""></section>
	 
	 <div id="content" class="inner">

		 @if(isset($page) && !$page->contents->isEmpty())
			 <?php $chunk = $page->contents->chunk(3); ?>
			 @foreach($chunk as $contents)

				 <div class="row">
					 @foreach($contents as $content)
						 <div class="col-md-4 homepageBlock">
                             <div class="{{ !empty($content->style) ? $content->style : '' }}">
                                 <h5 class="line">{!! $content->title !!}</h5>
                                 {!! $content->content !!}
                             </div>
						 </div>
					 @endforeach
				 </div>

			 @endforeach
		 @endif

	 </div>
@stop
