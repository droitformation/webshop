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
					<h3>Inscrivez simplement votre adresse email pour recevoir les nouveautés du site.</h3>
                    <div class="row">
                        <div class="col-md-5 col-xs-12">
							@if(!$newsletters->isEmpty())
								@foreach($newsletters as $newsletter)
									<h4>{{ $newsletter->titre }}</h4>
									@include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'pubdroit'])
								@endforeach
							@endif
                        </div>
                    </div>
                    <p>Je souhaite me <a href="{{ url('pubdroit/unsubscribe') }}">désinscrire</a></p>
				</div>
			</div>

		</div>
	</section>

@stop