@extends('frontend.bail.layouts.master')
@section('content')

	<div id="content" class="inner inner-app">
		<div class="row">
			<div class="col-md-8">
				<h3 class="line up">Désinscription</h3>
				<h3>Entrez votre adresse email pour vous <strong>désinscrire</strong></h3>
				<div class="row">
					<div class="col-md-5 col-xs-12">
						@if(!$newsletters->isEmpty())
							@foreach($newsletters as $newsletter)
								<h4>{{ $newsletter->titre }}</h4>
								@include('newsletter::Frontend.partials.unsubscribe', ['newsletter' => $newsletter, 'return_path' => 'bail'])
							@endforeach
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-4">
				@include('frontend.bail.partials.sidebar')
			</div>
		</div>
	</div>

@stop