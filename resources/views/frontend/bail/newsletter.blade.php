@extends('frontend.bail.layouts.master')
@section('content')

		<!-- Illustration -->

<div id="content" class="inner">

	<div class="row">
		<div class="col-md-12">
			<h3 class="line up">Newsletter</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

			@if(isset($campagne))
				<p><a href="{{ url('matrimonial/page/newsletter/'.$campagne->newsletter_id ) }}"><i class="fa fa-arrow-circle-left"></i> Retour</a></p>
				<h2>{{ $campagne->sujet }}</h2>
				<h3>{{ $campagne->auteurs }}</h3>

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
	</div>

</div>
@stop
