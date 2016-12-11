@extends('frontend.bail.layouts.master')
@section('content')

	<div id="content" class="inner inner-app">

		<div class="row">
			<div class="col-md-8">
				<h3 class="line up">{{ $page->title }}</h3>

				<div class="row">
					<div class="col-md-5">
						<div class="thumbnail" style="display: inline-block;">
							<img style="max-height: 350px;" src="{{ asset('files/products/'.$revue->image) }}" alt="{{ $revue->title }}">
						</div>
					</div>
					<div class="col-md-7">
						<h4 class="revue-title">{{ $revue->title }}</h4>
						<p>{{ $revue->teaser }}</p>
						<p>
							<a href="{{ url('pubdroit/product/'.$revue->id) }}" class="btn btn-sm btn-default">
								<i class="fa fa-shopping-cart"></i> &nbsp;Commander
							</a>
							<a href="{{ url('pubdroit/product/'.$revue->id) }}" class="btn btn-sm btn-info">
								<i class="fa fa-download"></i> &nbsp;Télécharger
							</a>
						</p>
					</div>
				</div>

				{!! $page->content !!}

			</div>
			<div class="col-md-4">
				@include('frontend.bail.partials.sidebar')
			</div>
		</div>

	</div>

@stop
