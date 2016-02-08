@if(!Cart::content()->isEmpty())

    <div class="row">
        <figure class="col-md-4 first">
            <div class="cart-option-box">
                <h4><i class="fa fa-money"></i> RABAIS</h4>
                <p>Entrer votre code</p>
                {!! Form::open(array('url' => 'cart/applyCoupon')) !!}

                <div class="input-group">
                    <input type="text" value="" name="coupon" id="inputDiscount" class="form-control" placeholder="Code">
                    <span class="input-group-btn">
                        <button class="more-btn" type="submit">Appliquer</button>
                    </span>
                </div><!-- /input-group -->
                {!! Form::close() !!}
            </div>
        </figure>
        <figure class="col-md-4"></figure>
        <figure class="col-md-4 price-total">
            <div class="cart-option-box cart-option-box-checkout">
                <table width="100%" border="0" cellpadding="10" class="total-payment">
                    @if(isset($coupon) && !empty($coupon))
                        <tr>
                            <td width="55%" align="left">Rabais appliqué</td>
                            <td width="45%" align="right">
                                @if($coupon['type'] == 'shipping')
                                    <span class="text-muted">Frais de port offerts</span>
                                @else
                                    <span class="text-muted">{{ $coupon['title'] }}</span> &nbsp;{{ $coupon['value'] }}%
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td align="left"><h4>TOTAL</h4></td>
                        <td align="right"><h4>{{ number_format((float)Cart::total(), 2, '.', '') }} CHF</h4></td>
                    </tr>
                </table>
                <p class="text-right"><a href="{{ url('checkout/confirm') }}" class="more-btn">Aller au checkout</a></p>
            </div>
        </figure>
    </div>
@endif