@extends('frontend.matrimonial.layouts.master')
@section('content')

	<div id="content" class="inner inner-app">

		<div class="row">
			<div class="col-md-8">

				@if(isset($page))
					<h3 class="line">{{ $page->title }}</h3>
					{!! $page->content !!}

					@if(!$page->contents->isEmpty())
						<?php $styles = $page->contents->groupBy('type'); ?>
						@foreach($styles as $style => $contents)
							@include('frontend.matrimonial.partials.'.$style, ['contents' => $contents])
						@endforeach
					@endif
				@endif

			</div>
			<div class="col-md-4">
				@include('frontend.matrimonial.partials.sidebar')
			</div>
		</div>

	</div>

@stop
