@extends('emails.layouts.admin')
@section('content')

    <h3>Nouvelle {{ $what }}</h3>
    <p>Nom: {{ $name }}</p>
    {!! isset($colloque) ? '<p>Au colloque: <strong>'.$colloque.'</strong></p>' : ''  !!}
    {!! isset($order) ? '<p>Commande no: <strong>'.$order.'</strong></p>' : ''  !!}
    <p><a href="{{ $link }}">Voir {{ $what }}</a></p>

@stop