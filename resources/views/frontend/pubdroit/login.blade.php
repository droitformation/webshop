@extends('layouts.pubdroit')

@section('content')
	
    <div class="welcome">
	{{ Form::open(array('url' => 'login' , 'class' => 'pure-form pure-form-aligned')) }}
		<h3>Login</h3>
	
		<!-- if there are login errors, show them here -->
		@if (Session::get('loginError'))
		<div class="alert alert-danger">{{ Session::get('loginError') }}</div>
		@endif
		
		<fieldset>
		<p>
			{{ $errors->first('email') }}
			{{ $errors->first('password') }}
		</p>
	
		<div class="pure-control-group">
			{{ Form::label('email', 'Email Address') }}
			{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
		</div>
		<div class="pure-control-group">
			{{ Form:: label('password', 'Password') }}
			{{ Form::password('password') }}
		</div>
		
		</fieldset>
	
		<p>{{ Form::submit('Submit!' , ['class' => 'pure-button'] ) }}</p>
	{{ Form::close() }}
    </div>
	
@stop
