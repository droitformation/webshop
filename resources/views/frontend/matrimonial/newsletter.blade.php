@extends('frontend.matrimonial.layouts.master')
@section('content')


<div id="content" class="inner inner-app">

	<div class="row">
		<div class="col-md-8 homepageBlock">

			<h3 class="line">Newsletter</h3>

			@if(isset($campagne))

				<div class="post">
					<div class="post-title">
						<h3>{{ $campagne->sujet }}</h3>
						<p class="text-abstract-app">{{ $campagne->auteurs }}</p>
					</div>
				</div>

				<hr/>

				<div id="newsletter">
					@if(!$campagne->content->isEmpty())
						@foreach($campagne->content as $bloc)
							{!! view('frontend.newsletter.content.'.$bloc->type->partial)->with(['bloc' => $bloc ])->__toString() !!}
						@endforeach
					@endif
				</div>
			@else
				<p>Encore aucune newsletter</p>
			@endif

		</div>
		<div class="col-md-4">
			@include('frontend.matrimonial.partials.sidebar')
		</div>
	</div>

</div>

@stop
