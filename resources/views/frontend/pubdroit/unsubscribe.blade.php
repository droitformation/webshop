@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>Newsletter</h2>
				<span class="h-line"></span>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h3>Entrez votre adresse email pour vous <strong>désinscrire</strong></h3>
                    <div class="row">
                        <div class="col-md-5 col-xs-12">
							@if(!$newsletters->isEmpty())
								@foreach($newsletters as $newsletter)
									<h4>{{ $newsletter->titre }}</h4>
									@include('frontend.newsletter.partials.unsubscribe', ['newsletter' => $newsletter, 'return_path' => 'pubdroit'])
								@endforeach
							@endif
                        </div>
                    </div>
				</div>
			</div>

		</div>
	</section>

@stop