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
                        <img height="150px" src="{{ secure_asset('files/colloques/illustration/'.$illustraton) }}" alt=""/>
                    </a>
                </p>
                <!-- Greeting -->
                <h1 style="{{ $style['header-2'] }}">{{ $colloque->titre }}<br/></h1>
                <p style="color: #000;margin-top: 15px;margin-bottom: 10px;">{{ $colloque->event_date }}</p>
                <p style="color: #000;margin-top: 15px;margin-bottom: 20px;">{{ $texte }}</p>
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
                <p style="{{ $style['paragraph'] }}font-weigth:normal;margin-top: 40px;margin-bottom:0;font-size: 14px;">Le secrétariat de la Faculté de droit</p>

            </td>
        </tr>
    </table>
@stop
