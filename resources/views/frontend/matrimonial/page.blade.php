@extends('frontend.matrimonial.layouts.master')
@section('content')

	<div id="content" class="inner">
		<div class="row">
			<div class="col-md-12">
				<h3 class="line up">{{ $page->title }}</h3>
				{!! $page->content !!}
			</div>
		</div>

		@if(!$page->contents->isEmpty())
			<?php $styles = $page->contents->groupBy('type'); ?>
			@foreach($styles as $style => $contents)
				@include('frontend.matrimonial.partials.'.$style, ['contents' => $contents])
			@endforeach
		@endif

	 </div>
@stop
