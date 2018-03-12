@extends('emails.layouts.notification')
@section('content')

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('/pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}"><h1 style="{{ $style['header-2'] }}text-align:left;">Adresse email invalide</h1></td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}padding:5px 35px 35px 35px;">

                <div style="{{ $style['paragraph'] }}">
                    <p>Le message envoyé à l'adresse {!! $bounce !!} n'a pas pu être remis.</p>
                    <p><strong>Erreur:</strong></p>
                    @if($error == 'bounced')
                        <p>Le serveur de messagerie du destinataire indique que l'adresse du destinataire n'existe pas.</p>
                    @else
                        <p>Il y a plusieurs raisons pour lesquelles le relay cesse de tenter de livrer des messages et les supprime, y compris: les rebonds, les messages ayant atteint leur limite de tentatives, les adresses précédemment désabonnées / rejetées / réclamées ou les adresses rejetées par un ESP.</p>
                    @endif
                </div>

                @if(isset($remarque) && !empty($remarque))
                    <div style="{{ $style['paragraph'] }}">
                        <h3>Pour webmaster:</h3>
                       <?php
                            echo '<pre>';
                            print_r($remarque);
                            echo '</pre>';
                        ?>
                    </div>
                @endif

            </td>
        </tr>
    </table>

@stop