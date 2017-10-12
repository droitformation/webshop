@extends('emails.layouts.notification')
@section('content')

    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <h1 style="{{ $style['header-2'] }}text-align:left;">Alerte</h1>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:5px 35px 35px 35px;">
                <p style="{{ $style['paragraph'] }}">Une erreur est survenue</p>
                <p style="{{ $style['paragraph'] }}">
                    <?php
                        echo '<pre>';
                        print_r($error);
                        echo '</pre>';
                    ?>
                </p>
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:5px 35px 35px 35px;">
                <p style="{{ $style['paragraph'] }}"><a href="{{ url('/admin') }}">Aller vers l'administration</a></p>
            </td>
        </tr>
    </table>

@stop
