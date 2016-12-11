@extends('frontend.bail.layouts.master')
@section('content')

	<!-- Illustration -->
	<section><img src=" {{ asset('/images/bail/header.jpg') }}" alt=""></section>

	<div id="content" class="inner inner-app ">

		<div class="row">
			<div class="col-md-8">

				@if(isset($page) && !$page->contents->isEmpty())
					<?php $chunk = $page->contents->sortBy('rang')->chunk(2); ?>

					@foreach($chunk as $contents)

						<div class="row home-bloc">
							@foreach($contents as $content)
								<div class="col-md-6">
									<h5 class="line">{!! $content->title !!}</h5>
									<div class="{{ !empty($content->style) ? $content->style : '' }}">
										{!! $content->content !!}
									</div>
								</div>
							@endforeach
						</div>

					@endforeach
				@endif

			</div>
			<div class="col-md-4">
				@include('frontend.bail.partials.sidebar')
			</div>
		</div>
	</div>

@stop
