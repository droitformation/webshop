@extends('emails.layouts.notification')
@section('content')

    <?php
    $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
    $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
    $emptyCell     = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';
    $tdTableTitle = '-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 40px;padding-top: 6px;padding-bottom: 6px;padding-left: 25px;padding-right: 25px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #505050;background-color: #ebebeb;border-bottom: 1px solid #e3e3e3;border-top: 1px solid #e3e3e3;';
    $desktopHide  = 'display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;';
    $tdTableRow   = 'padding-top: 5px;padding-bottom: 5px;padding-left: 25px;padding-right: 25px;height:30px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: middle;font-size: 14px;line-height: 16px;color: #54565c;background-color: #ffffff;border-bottom: 1px solid #e3e3e3;';
    ?>

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner_full'] }}" align="center" width="600" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }} padding: 35px 25px 15px 25px;">
                <div style="{{ $style['paragraph'] }}">
                    <h2 style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">
                        Bonjour {{ $user->name }}
                    </h2>
                    <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">
                        Nous vous remercions pour votre demande d'abonnement sur <a href="http://www.publications-droit.ch" style="color: #1a446e;">www.publications-droit.ch</a>.
                    </p>
                    <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">Le premier ouvrage vous sera envoyé dans les plus brefs délais.</p>
                    <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }} color: #000; margin-top: 15px;"><strong>Résumé:</strong></p>
                </div>
            </td>
        </tr>
        <td class="blank" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoiceTable2" style="{{ $resetMargin }}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;background-color: #ffffff;">
                <tr>
                    <th class="width380 alignRight" style="{{ $tdTableTitle }} text-align: left;">Titre</th>
                    <th class="width40 alignRight" style="{{ $tdTableTitle }} text-align: left;"></th>
                    <th class="width40 alignRight" style="{{ $tdTableTitle }}">Prix</th>
                </tr>

                @if($abos)
                    @foreach($abos as $abo)
                    <tr>
                        <td style="{{ $resetMargin }} width: 390px; {{ $tdTableRow }} text-align: left;">
                            <strong>{{ $abo->abo->title }}</strong>
                        </td>
                        <td class="" style="{{ $resetMargin }}width: 30px; {{ $tdTableRow }} text-align: center;"></td>
                        <td class="alignRight" style="{{ $resetMargin }}width: 160px; {{ $tdTableRow }}">
                            <span class="desktopHide" style="{{ $desktopHide }}">Subtotal: </span>
                            <span class="amount" style="color: #000000;">{{ $abo->abo->price_cents }}/{{ strtolower($abo->abo->plan_fr) }} CHF</span>
                        </td>
                    </tr>
                    @endforeach
                @endif

                <tr>
                    <td colspan="2" class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 25px;padding-right:25px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 312px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #000;background-color: #fbfbfb;"><strong>Total</strong><br/></td>
                    <td class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 25px;padding-right: 25px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width:124px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #fbfbfb;">
                        <span class="amount" style="color: #000000;font-size: 16px;font-weight: bold;">{{ $total }} CHF</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    @include('emails.partials.footer')
    </table>

@stop
