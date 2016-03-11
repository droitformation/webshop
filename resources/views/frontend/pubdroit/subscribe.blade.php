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
					<h3>Inscrivez simplement votre adresse email pour recevoir les nouveautés du site.</h3>
                    <div class="row">
                        <div class="col-md-5 col-xs-12">
                            @include('frontend.partials.subscribe', ['newsletter_id' => $newsletter_id])
                        </div>
                    </div>
                    <p>Je souhaite me <a href="{{ url('unsubscribe') }}">désinscrire</a></p>
				</div>
			</div>

		</div>
	</section>

@stop