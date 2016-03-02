@extends('auth.layouts.master')
@section('content')

    <h1 class="auth-logo text-center">
        <a class="text-inverse" href="{{ url('/') }}">
            Droit Formation | <span class="text-danger">Administration</span>
        </a>
    </h1>

    <div class="alert alert-success" role="alert">{{ $message }}</div>

@stop
