@extends('frontend.bail.layouts.master')
@section('content')

	<div id="content" class="inner">
		<div class="row">
			<div class="col-md-12">
				<h3 class="line up">{{ $page->title }}</h3>
				{!! $page->content !!}

				@if(isset($faqcats) && !$faqcats->isEmpty())
					<div class="faq_list">
						@foreach($faqcats as $categorie)
							<p><a class="{{ $current == $categorie->id ? 'active' : '' }}" href="{{ url('bail/page/faq/'.$categorie->id) }}">{{ $categorie->title }}</a></p>
						@endforeach
						<div class="clearfix"></div>
					</div>
				@endif

			</div>
		</div>

		@if(!$page->contents->isEmpty())
			<?php $styles = $page->contents->groupBy('type'); ?>
			@foreach($styles as $style => $contents)
				@include('frontend.bail.partials.'.$style, ['contents' => $contents])
			@endforeach
		@endif

		@if(isset($questions) && !$questions->isEmpty())
			<div class="row">
				<div class="col-md-12">
					@foreach($questions as $question)

						<div class="faq_wrapper">
							<h3>{!! $question->title !!}</h3>
							<div>
								<cite>{!! $question->question !!}</cite>
								{!! $question->answer !!}
							</div>
						</div>

					@endforeach
				</div>
			</div>
		@endif

	 </div>
@stop
