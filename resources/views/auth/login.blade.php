@extends('auth.layouts.master')
@section('content')

	@include('auth.partials.login-form')

	@if(!session()->has('admin'))
        <p class="line-delimiter">Ou</p>
		<p><a href="{{ url('register') }}" class="btn btn-block btn-primary">Je n'ai pas encore de compte</a></p>
		<br/>
	@endif
@stop
