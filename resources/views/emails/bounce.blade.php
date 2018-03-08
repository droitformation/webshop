@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('/pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <h1 style="{{ $style['header-2'] }}text-align:left;">Adresse email invalide</h1>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:5px 35px 35px 35px;">

                <div style="{{ $style['paragraph'] }}">
                    <p>Le message envoyé à l'adresse {!! $bounce !!} n'a pas pu être remis.</p>
                </div>

            </td>
        </tr>
    </table>

@stop