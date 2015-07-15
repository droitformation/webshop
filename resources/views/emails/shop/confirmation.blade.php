@extends('emails.layouts.shop')
@section('content')

    <?php

        $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
        $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
        $emptyCell     = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';
        $tdTableTitle = '-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 40px;padding-top: 6px;padding-bottom: 6px;padding-left: 12px;padding-right: 12px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #505050;background-color: #ebebeb;border-bottom: 1px solid #e3e3e3;border-top: 1px solid #e3e3e3;';
        $desktopHide  = 'display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;';
        $tdTableRow   = 'padding-top: 5px;padding-bottom: 5px;padding-left: 12px;padding-right: 12px;height:30px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: middle;font-size: 14px;line-height: 16px;color: #54565c;background-color: #ffffff;border-bottom: 1px solid #e3e3e3;';
    ?>
    <tr>
        <td class="eBody" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 8px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">
            <h2 style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">
                Bonjour {{ $order->user->name }}
            </h2>
            <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">
                Nous vous remercions pour votre commande sur <a href="http://www.publications-droit.ch" style="color: #1a446e;">www.publications-droit.ch</a>.<br/>
                Résumé de votre commande
            </p>
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
                    <th class="width40 alignRight" style="{{ $tdTableTitle }} text-align: left;">Qt</th>
                    <th class="width380 alignRight" style="{{ $tdTableTitle }} text-align: left;">Titre</th>
                    <th class="width40 alignRight" style="{{ $tdTableTitle }}">Prix</th>
                </tr>

                @if($products)
                    @foreach($products as $product_id => $product)

                    <?php $qty = $product->count(); ?>
                    <tr>
                        <td class="" style="{{ $resetMargin }}width: 30px; {{ $tdTableRow }} text-align: center;">
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
                    <td colspan="2" class="subTotal alignRight mobileHide" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;font-size: 14px;line-height: 22px;color: #656565;background-color: #fff;">
                        {!! ($order->coupon_id > 0 ? 'Rabais: '.$order->coupon->title.'<br>' : '') !!}
                        Subtotal:<br>
                        Frais de port:
                    </td>
                    <td class="width84 subTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 124px;font-size: 14px;line-height: 22px;color: #656565;background-color: #fff;">

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
                    <td colspan="2" class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 312px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #000;background-color: #f6f6f7;">
                        <strong>Total</strong><br/>
                        @if($order->payement_id == 1)
                            <small>payable jusqu'au {{ $duDate }}</small>
                        @endif
                    </td>
                    <td class="eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 6px;padding-right: 6px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width:124px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #f6f6f7;">
                        <span class="amount" style="color: #000000;font-size: 16px;font-weight: bold;">{{ $order->total_with_shipping }} CHF</span>
                    </td>
                </tr>

            </table>

        </td>
        <!-- end invoice content-->
    </tr>

    <!-- end .eBody -->

@stop