@extends('frontend.matrimonial.layouts.master')
@section('content')

    <!-- Illustration -->
    <section id="photo"><img src=" {{ asset('/images/matrimonial/home.jpg') }}" alt=""></section>

    <div id="content" class="inner">
		@if(isset($page))
			<div class="row">
				<div class="col-md-12 homepageBlock">
					<h1>{{ $page->title }}</h1>
					{!! $page->content !!}
				</div>
			</div>

			@if(!$page->contents->isEmpty())
				<h5 class="line">News</h5>
				<?php $chunk = $page->contents->chunk(3); ?>
				@foreach($chunk as $contents)
					<div class="row">
						@foreach($contents as $content)
							<div class="col-md-4 homepageBlock">
								<div class="{{ !empty($content->style) ? $content->style : '' }}">
									<h5>{!! $content->title !!}</h5>
									{!! $content->content !!}
								</div>
							</div>
						@endforeach
					</div>
				@endforeach

			@endif
		@endif
    </div>
		
@stop
