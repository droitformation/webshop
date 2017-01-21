@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>{{ $page->title }}</h2>
				<span class="h-line"></span>
			</div>

			<div class="row">
				<div class="col-md-12">
					{!! $page->content !!}
				</div>
			</div>

		</div>
	</section>

@stop