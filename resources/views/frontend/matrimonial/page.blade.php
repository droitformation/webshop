@extends('frontend.matrimonial.layouts.master')
@section('content')

	<div id="content" class="inner">
		<div class="row">
			<div class="col-md-12">
				<h3 class="line up">{{ $page->title }}</h3>
				{!! $page->content !!}
			</div>
		</div>

		@if(!$page->blocs->isEmpty())
			<?php $styles = $page->blocs->groupBy('type'); ?>
			@foreach($styles as $style => $blocs)
				@include('frontend.matrimonial.partials.'.$style, ['blocs' => $blocs])
			@endforeach
		@endif

	 </div>
@stop
