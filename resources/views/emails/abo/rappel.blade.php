@extends('emails.layouts.notification')
@section('content')

    <?php
    $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
    $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
    $emptyCell     = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';
    $tdTableTitle = '-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 40px;padding-top: 6px;padding-bottom: 6px;padding-left: 12px;padding-right: 12px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #505050;background-color: #ebebeb;border-bottom: 1px solid #e3e3e3;border-top: 1px solid #e3e3e3;';
    $desktopHide  = 'display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;';
    $tdTableRow   = 'padding-top: 5px;padding-bottom: 5px;padding-left: 12px;padding-right: 12px;height:30px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: middle;font-size: 14px;line-height: 16px;color: #54565c;background-color: #ffffff;border-bottom: 1px solid #e3e3e3;';
    ?>

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>

    <table style="{{ $style['email-body_inner_full'] }}" align="center" width="600" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }} padding: 25px 25px 20px 25px;">
                <div style="{{ $style['body_content'] }}">
                    <h2 style="{{$resetMargin}}margin-bottom:5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">Bonjour {{ $abonnement->user->name }}</h2>
                    <p style="{{$resetMargin}}margin-bottom:10px;{{ $resetPadding }}color:#9b1a1a;">Après vérification de notre comptabilité, nous nous apercevons que la facture concernant l'abonnement susmentionné est due.</p>
                </div>

                <!--infos colloque -->
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoiceTable2" style="{{ $resetMargin }}{{ $resetPadding }}mso-table-lspace: 0pt; border: 1px solid #e3e3e3;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;background-color: #ffffff;margin: 20px 0;">
                    <tr>
                        <th class="width380 alignRight" style="{{ $tdTableTitle }} text-align: left;">Titre</th>
                        <th class="width40 alignRight" style="{{ $tdTableTitle }} text-align: left;"></th>
                        <th class="width40 alignRight" style="{{ $tdTableTitle }}">Prix</th>
                    </tr>
                    <tr>
                        <td style="{{ $resetMargin }} width: 390px; {{ $tdTableRow }} text-align: left;">
                            <strong>{{ $abo->title }}</strong>
                        </td>
                        <td class="" style="{{ $resetMargin }}width: 30px; {{ $tdTableRow }} text-align: center;"></td>
                        <td class="alignRight" style="{{ $resetMargin }}width: 160px; {{ $tdTableRow }}">
                            <span class="desktopHide" style="{{ $desktopHide }}">Subtotal: </span>
                            <span class="amount" style="color: #000000;">{{ $abo->price_cents }}/{{ strtolower($abo->plan_fr) }} CHF</span>
                        </td>
                    </tr>
                </table>

                <div style="{{ $style['body_content'] }}">
                    <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                        <strong>A toutes fins utiles, les coordonnées ci-après vous permettront le règlement de votre facture via Internet.</strong>
                    </p>
                    <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                        <li>IBAN: {{ Registry::get('inscription.infos.iban') }}</li>
                        <li>BIC: {{ Registry::get('inscription.infos.bic') }}</li>
                    </ul>

                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}color:#9b1a1a;">Merci de bien vouloir prendre connaissance des documents joints et de faire le versement au plus vite. </p>
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}">Nous restons à disposition pour tout renseignement et vous adressons nos meilleures salutations.</p>
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}color:#000;"><strong>Le secrétariat de la Faculté de droit</strong></p>
                </div>

            </td>
        </tr>

        @include('emails.partials.footer')
    </table>
    <!-- end .eBody -->

@stop