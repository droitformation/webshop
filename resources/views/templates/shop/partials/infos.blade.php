<table id="content-table">
    <tr><td colspan="2" height="5">&nbsp;</td></tr>
    <tr>
        <!-- Messages for customer -->
        <td width="62%" align="top" valign="top">

            <h3>Communications</h3>
            <div class="communications">

                @if($generate->order()->payed_at)
                    <p class="message special">Acquitté le {{ $generate->order()->payed_at->format('d/m/Y') }}</p>
                @endif

                @if(!empty(\Registry::get('shop.avertissement')))
                    <div class="important">
                        {!! \Registry::get('shop.avertissement') !!}
                    </div>
                @endif

                @if(!empty($messages) && !empty(config('generate.order.msgTypes')))
                    @foreach(config('generate.order.msgTypes') as $msgType)
                        @if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                            <p class="message {{ $msgType }}">{{ $messages[$msgType] }}</p>
                        @endif
                    @endforeach
                    <br/>
                @endif

                <p class="message">{{ $messages['remerciements'] }}</p><br/>
                <p class="message">Neuchâtel, le <?php echo $date; ?></p>
            </div>

        </td>
        <td width="5%" align="top" valign="top"></td>
        <!-- Total calculations -->
        <td width="33%" align="top" valign="top" class="text-right">
            <table width="100%" id="content-table" class="total_line" align="right" valign="top">

                @if($generate->order()->coupon_id > 0 && $generate->showCoupon())
                    <tr align="top" valign="top">
                        <td width="40%" align="top" valign="top" class="text-right text-muted">
                            {!! $generate->order()->coupon->coupon_title !!}
                        </td>
                        <td width="60%" align="top" valign="top" class="text-right">
                            {{ $generate->order()->coupon->coupon_value }}
                        </td>
                    </tr>
                @endif

                <tr align="top" valign="top">
                    <td width="40%" align="top" valign="top" class="text-right" style="border: none;"><strong>Sous-total:</strong></td>
                    <td width="60%" align="top" valign="top" class="text-right" style="border: none;">{{ $generate->order()->price_cents }} CHF</td>
                </tr>
                <tr align="top" valign="top">
                    <td width="40%" align="top" valign="top" class="text-right" style="border: none;"><strong>Frais de port:</strong></td>
                    <td width="60%" align="top" valign="top" class="text-right" style="border: none;">{{ $generate->order()->total_shipping }} CHF</td>
                </tr>

                <tr align="top" valign="top">
                    <td width="40%" align="top" valign="top" class="text-right line_total_invoice"><strong>Total:</strong></td>
                    <td width="60%" align="top" valign="top" class="text-right line_total_invoice">
                        <strong>{{ $generate->order()->total_with_shipping }} CHF</strong>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>