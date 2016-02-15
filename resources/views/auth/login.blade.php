@extends('auth.layouts.master')
@section('content')

    <h1 class="auth-logo text-center">
        <a class="text-inverse" href="{{ url('/') }}">
            @if(isset($admin))
                Droit Formation | <span class="text-danger">Administration</span>
            @else
                <img style="height: 75px; width:380px;" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" />
            @endif
        </a>
    </h1>

    <p><a href="{{ url('/') }}" class="text-danger"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a></p>
	<form class="form-horizontal form-validation" role="form" method="POST" action="/auth/login">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="panel-body">
			<h4 class="text-center" style="font-size:14px;margin-bottom: 15px;margin-top:0;">Login</h4>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="email" class="form-control" name="email" value="{{ old('email') }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
					</div>
				</div>
			</div>
			<div class="clearfix">
				<div class="pull-right"><label><input type="checkbox" style="margin-bottom: 0px" checked=""> Se souvenir de moi</label></div>
			</div>
		</div>
		<div class="panel-footer">
			<a href="{{ url('password/email') }}" class="pull-left text-inverse" style="padding-left:0">Mot de passe perdu?</a>
			<div class="pull-right">
				<button type="submit" class="btn btn-inverse">Envoyer</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</form>

    <p class="line-delimiter">Ou</p>

	@if(!isset($admin))
		<p><a href="{{ url('auth/register') }}" class="btn btn-block btn-inverse">Je n'ai pas encore de compte</a></p>
		<br/>
	@endif
@stop
