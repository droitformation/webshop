@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('/pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <h1 style="{{ $style['header-2'] }}text-align:left;">Nouvelle notification: {{ $what }}</h1>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:5px 35px 35px 35px;">

                {!! isset($colloque) ? '<p '.$style['paragraph'].'>'.$name.' vient de s\'inscrire au colloque<br/> <strong>'.$colloque.'</strong></p>' : ''  !!}
                {!! isset($order) ? '<p '.$style['paragraph'].'>Commande passé par '.$name.'<br/> n° <strong>'.$order.'</strong></p>' : ''  !!}
                {!! isset($rappel) ? '<p '.$style['paragraph'].'>n° <strong>'.$rappel.'</strong></p>' : ''  !!}

                <div style="{{ $style['paragraph'] }}">
                    <p>{{ $name }}</p>
                    @if(isset($abos))
                        <ul>
                            @foreach($abos as $abo)
                                <li><a href="{{ url('admin/abonnement/'.$abo->id) }}">{{ $abo->abo->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <p style="{{ $style['paragraph'] }}"><a href="{{ $link }}">Voir {{ $what }}</a></p>
            </td>
        </tr>
    </table>

@stop
