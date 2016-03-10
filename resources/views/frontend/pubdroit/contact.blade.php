@extends('frontend.pubdroit.layouts.master')
@section('content')

	<section class="row">
		<div class="col-md-12">

			<p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

			<div class="heading-bar">
				<h2>{{ $page->title }}</h2>
				<span class="h-line"></span>
			</div>

			{!! $page->content !!}

			<div class="row">
				<div class="col-md-8">
					<form action="{{ url('sendMessage') }}" class="form-horizontal" method="post">
						{!! csrf_field() !!}
						<div class="form-group">
							<label class="col-md-2 control-label">Nom</label>
							<div class="col-md-10">
								<input type="text" name="name" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Email</label>
							<div class="col-md-10">
								<input type="email" name="email" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Remarque</label>
							<div class="col-md-10">
								<textarea name="remarque" required class="form-control" rows="3"></textarea>
							</div>
						</div>
						<input type="hidden" name="site" value="{{ $site->id }}">
						<input value="Envoyer" class="btn btn-default submit-btn" type="submit" />
					</form><!--END CONTACT FORM-->
				</div>
				<div class="col-md-4">

					<h4>{!! Registry::get('shop.infos.nom') !!}</h4>
					<p>{!! Registry::get('shop.infos.adresse') !!}</p>
					<p><a href="mailto:{{ Registry::get('shop.infos.email') }}">{{ Registry::get('shop.infos.email') }}</a></p>

				</div>
			</div>

		</div>
	</section>

@stop