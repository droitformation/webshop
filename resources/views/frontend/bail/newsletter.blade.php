@extends('frontend.bail.layouts.master')
@section('content')

<div id="content" class="inner inner-app">

	<div class="row">
		<div class="col-md-8">

			<h3 class="line up">Newsletter</h3>

			@if(isset($campagne))
				<h3>{{ $campagne->sujet }}</h3>
				<p>{{ $campagne->auteurs }}</p>

				<hr/>

				<div id="newsletter">
					@if(!$campagne->content->isEmpty())
						@foreach($campagne->content as $bloc)
							{!! view('newsletter::Frontend.content.'.$bloc->type->partial)->with(['bloc' => $bloc ])->__toString() !!}
						@endforeach
					@endif
				</div>
			@else
				<p>Encore aucune newsletter</p>
			@endif

		</div>
		<div class="col-md-4">
			@include('frontend.bail.partials.sidebar')
		</div>
	</div>
</div>

@stop
