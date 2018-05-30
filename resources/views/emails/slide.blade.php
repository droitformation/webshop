@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <p style="margin-top: 0;">
                    <a style="margin-top: 0;" href="{{ secure_url('pubdroit/colloque/'.$colloque->id) }}">
                        <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                        <img height="120px" src="{{ secure_asset('files/colloques/illustration/'.$illustraton) }}" alt=""/>
                    </a>
                </p>
                <!-- Greeting -->
                <h1 style="{{ $style['header-2'] }}">{{ $colloque->titre }}<br/></h1>
                <p style="color: #000;margin-top: 5px;margin-bottom: 25px;font-style: italic;">{{ $colloque->event_date }}</p>
                <div style="color: #000;margin-top: 15px;margin-bottom: 20px; text-align: left;">
                    {!! \Registry::get('slides.texte') !!}
                </div>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_content'] }}">
                <!-- Intro -->
                <table style="{{ $style['body_action'] }}margin:0 auto 20px auto;" align="center" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <a href="{{ $url }}"
                               style="{{ $fontFamily }} {{ $style['button'] }} {{ $style['button--blue'] }}"
                               class="button" target="_blank">Lien vers le téléchargement
                            </a>
                        </td>
                    </tr>
                </table>

                <!-- Salutation -->
                <div style="{{ $style['paragraph'] }}font-weigth:normal;color:#1b1b1b;margin-top: 40px;margin-bottom:0;font-size: 14px;">
                    {!! \Registry::get('slides.adresse') !!}
                </div>

            </td>
        </tr>
    </table>
@stop
