@extends('sondages.layouts.master')
@section('content')

    @include('flash::message')

    @if(isset($sondage) && !$sondage->marketing)
        <p><a class="btn btn-default" href="{{ url('/') }}"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour à publications-droit.ch</a></p>
    @else
        @if($sondage->signature)
            <h3 style="margin-top: 30px;"><strong>{{ $sondage->signature }}</strong></h3>
        @else
            <h3 style="margin-top: 30px;"><strong>Le secrétariat de la Faculté de droit</strong></h3>
        @endif
    @endif

@stop