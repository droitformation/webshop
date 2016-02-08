@extends('auth.layouts.master')
@section('content')

	<h1 class="auth-logo text-center">
		<a class="text-inverse" href="{{ url('/') }}">
			<img style="height: 75px; width:380px;" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" />
		</a>
	</h1>
	<p><a href="{{ url('/') }}" class="text-danger"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a></p>
	<form class="form-horizontal" id="login" role="form" method="POST" action="/auth/register">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="panel-body">
			<h3 class="text-center" style="margin-bottom: 15px;margin-top:0;">Créer un compte</h3>
			<div class="form-group">
				<div class="col-sm-12">
					<label>Prénom</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" autocomplete="off" name="first_name" value="{{ old('first_name') }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<label>Nom</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" autocomplete="off" name="last_name" value="{{ old('last_name') }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<label>Email</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input type="email" class="form-control" autocomplete="off" id="email" name="email" value="{{ old('email') }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<label>Mot de passe</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" autocomplete="off" id="password" name="password">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<label>Confirmation du mot de passe</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="pull-right">
				<button type="submit" class="btn btn-inverse">Envoyer</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</form>

@stop
