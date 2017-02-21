@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <h1 style="{{ $style['header-2'] }}text-align:left;">Demande depuis <br/><small style=" font-size: 20px; color: #1c4d79;">{{ $site->nom }}</small></h1>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:15px 35px 35px 35px;">
                <div style="{{ $style['paragraph'] }}">
                    {!! nl2br($remarque) !!}
                </div>
                <p style="{{ $style['paragraph'] }}"><strong>{{ $name }}</strong> - <a href="mailto:{{ $email }}">{{ $email }}</a></p>
            </td>
        </tr>
    </table>

@stop