@extends('emails.layouts.shop')
@section('content')

    <?php

        $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
        $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
        $emptyCell     = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';
        $tdTableTitle = '-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 40px;padding-top: 0;padding-bottom: 6px;padding-left: 5px;padding-right: 5px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #656565;background-color: #ffffff;border-bottom: 1px solid #ebebeb;';
        $desktopHide  = 'display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;';
        $tdTableRow   = 'padding-top: 7px;padding-bottom: 7px;padding-left: 5px;padding-right: 5px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: middle;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;';
    ?>
    <tr>
        <td class="eBody" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 8px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;background-color: #f6f6f7;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">
            <h2 style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #1a446e;">
                Bonjour {{ $order->user->name }}
            </h2>
            <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">Résumé de votre commande</p>
        </td>
        <!-- end .eBody-->
    </tr>
    <tr>
        <td style="{{$resetMargin}}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;" height="10px"></td>
    </tr>
    <tr>
        <td class="blank" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoiceTable2" style="{{ $resetMargin }}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;background-color: #ffffff;">
                <tr>
                    <th class="width40" style="{{ $tdTableTitle }}">Qt</th>
                    <th class="width380 alignRight" style="{{ $tdTableTitle }} text-align: left;">Titre</th>
                    <th class="width40 alignRight" style="{{ $tdTableTitle }}">Prix</th>
                </tr>

                @if($products)
                    @foreach($products as $product_id => $product)

                    <?php $qty = $product->count(); ?>
                    <tr>
                        <td class="alignRight" style="{{ $resetMargin }}width: 30px; {{ $tdTableRow }}">
                            <span class="desktopHide" style="{{ $desktopHide }}">Qt: </span>{{ $qty }}
                        </td>
                        <td class="" style="{{ $resetMargin }} width: 390px; {{ $tdTableRow }} text-align: left;">
                            {{ $product->first()->title }}
                        </td>
                        <td class="alignRight" style="{{ $resetMargin }}width: 40px; {{ $tdTableRow }}">
                            <span class="desktopHide" style="{{ $desktopHide }}">Subtotal: </span>
                            <span class="amount" style="color: #000000;">{{ $product->first()->price_cents }} CHF</span>
                        </td>
                    </tr>
                    @endforeach
                @endif

                <tr>
                    <td colspan="2" class="subTotal alignRight mobileHide" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;font-size: 14px;line-height: 22px;color: #656565;background-color: #f6f6f7;">
                        {!! ($order->coupon_id > 0 ? 'Rabais: '.$order->coupon->title.'<br>' : '') !!}
                        Subtotal:<br>
                        Frais de port:
                    </td>
                    <td class="width84 subTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 124px;font-size: 14px;line-height: 22px;color: #656565;background-color: #f6f6f7;">

                        @if($order->coupon_id > 0)
                            <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">
                                {!! ( $order->coupon->type == 'shipping' ? 'Frais de port offerts' : 'Rabais: '.$order->coupon->title) !!}
                            </span>
                            <span class="amount" style="color: #54565c;">
                                 {!! ( $order->coupon->type == 'shipping' ? '' : '-'.$order->coupon->value.'%') !!}
                            </span><br>
                        @endif

                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">Subtotal: </span>
                        <span class="amount" style="color: #54565c;">{{ $order->price_cents }} CHF</span><br>
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">Frais de port:</span>
                        <span class="amount" style="color: #54565c;">{{ $order->shipping->price_cents }} CHF</span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 312px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <strong>Total</strong><br/>
                        <small>payable jusqu'au {{ $duDate }}</small>
                    </td>
                    <td class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width:124px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="amount" style="color: #000000;font-size: 16px;font-weight: bold;">{{ $order->total_with_shipping }} CHF</span>
                    </td>
                </tr>

            </table>

        </td>
        <!-- end invoice content-->
    </tr>
    <tr>
        <td class="eBody pdBt16" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 12px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;background-color: #ffffff;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">
            <table border="0" align="right" cellpadding="0" cellspacing="0" class="messageOptions" width="100%" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;text-align: right;">
                <tr>
                    <td align="left" class="moBtn pdRg16" style="{{ $resetMargin }}padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;">
                    </td>
                    <td align="right" class="moBtn" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;">
                        <table border="0" cellpadding="0" cellspacing="0" class="mainBtn" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;">
                            <tr>
                                {!! $emptyCell !!}
                                <td class="btnMain" style="{{ $resetMargin }}padding-top: 4px;padding-bottom: 4px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #1a446e;height: 18px;font-size: 14px;line-height: 18px;mso-line-height-rule: exactly;text-align: center;vertical-align: middle;">
                                    <a href="#" style="{{ $resetPadding }}display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;color: #ffffff;margin-left: auto;margin-right: 0;font-weight: normal;">
                                        <span style="text-decoration: none;color: #ffffff;">Votre facture en pdf</span>
                                    </a>
                                </td>
                                {!! $emptyCell !!}
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- end .eBody -->

@stop