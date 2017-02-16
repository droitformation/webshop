@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url($site->url) }}" target="_blank">
        <img width="max-width:100%;" src="{{ asset('images/'.$site->slug.'/header_email.png') }}" alt="{{ $site->nom }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <h1 style="{{ $style['header-2'] }}text-align:left;">Confirmation depuis<br/>
                    <small style=" font-size: 20px; color: #1c4d79;">{{ $site->nom }}</small>
                </h1>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:15px 35px 35px 35px;">
                <div style="{{ $style['paragraph'] }}">
                    Pour confirmer votre inscription à la newsletter sur {{ $site->nom }} veuillez suivre ce lien:
                </div>

                <table style="{{ $style['body_action'] }}" align="center" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <a href="{{ url('activation/'.$token.'/'.$newsletter_id) }}"
                               style="{{ $fontFamily }} {{ $style['button'] }} {{ $style['button--blue'] }}"
                               class="button" target="_blank">Confirmer l'adresse email
                            </a>
                        </td>
                    </tr>
                </table>

                <p style="{{ $style['paragraph'] }}"><a href="{{ url($site->url) }}">{{ $site->nom }}</a></p>
            </td>
        </tr>
    </table>

@stop
