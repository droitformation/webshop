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
					<h3>Inscrivez-vous simplement avec votre adresse email pour recevoir les nouveautés du site.</h3>
                    <div class="row">
                        <div class="col-md-5 col-xs-12">
							@if(!$newsletters->isEmpty())
								@foreach($newsletters as $newsletter)
									<h4>{{ $newsletter->titre }}</h4>
									<p><a data-fancybox data-type="iframe"
										  data-src="{{ url('site/subscribe/'.$newsletter->site->id) }}"
										  class="btn btn-default btn-profile btn-block"
										  href="javascript:;">
											Je m'inscrit!
										</a></p>
								@endforeach
							@endif
                        </div>
                    </div>
                    <p style="margin-top: 20px;">Je souhaite me
						<a data-fancybox data-type="iframe"
						   data-src="{{ url('site/unsubscribe/'.$newsletter->site->id) }}"
						   href="javascript:;">désinscrire</a></p>
				</div>
			</div>

		</div>
	</section>

@stop