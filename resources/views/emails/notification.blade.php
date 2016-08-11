@extends('emails.layouts.admin')
@section('content')

    <h3>Nouvelle {{ $what }}</h3>
    <p>Nom: {{ $name }}</p>
    {!! isset($colloque) ? '<p>Au colloque: <strong>'.$colloque.'</strong></p>' : ''  !!}
    {!! isset($order) ? '<p>Commande no: <strong>'.$order.'</strong></p>' : ''  !!}

    @if(isset($abos))
        <ul>
            @foreach($abos as $abo)
                <li>{{ $abo->abo->title }}</li>
            @endforeach
        </ul>
    @endif

    <p><a href="{{ $link }}">Voir {{ $what }}</a></p>

@stop