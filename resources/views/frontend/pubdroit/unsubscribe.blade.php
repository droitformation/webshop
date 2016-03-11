@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>Newsletter</h2>
				<span class="h-line"></span>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h3>Entrez votre adresse email pour vous <strong>désinscrire</strong></h3>
                    <div class="row">
                        <div class="col-md-5 col-xs-12">
                            @include('frontend.partials.unsubscribe', ['newsletter_id' => $newsletter_id])
                        </div>
                    </div>
				</div>
			</div>

		</div>
	</section>

@stop