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

	@include('auth.partials.login-form')

    <p class="line-delimiter">Ou</p>

	@if(!isset($admin))
		<p><a href="{{ url('auth/register') }}" class="btn btn-block btn-primary">Je n'ai pas encore de compte</a></p>
		<br/>
	@endif
@stop
