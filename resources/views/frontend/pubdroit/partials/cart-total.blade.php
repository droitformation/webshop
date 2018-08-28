@if(!Cart::instance('shop')->content()->isEmpty() || !Cart::instance('abonnement')->content()->isEmpty())

    @inject('cartworker','App\Droit\Shop\Cart\Worker\CartWorker')

    <div class="row">
        <figure class="col-md-4 first">
            @if(!Cart::instance('shop')->content()->isEmpty())
            <div class="cart-option-box">
                <h4><i class="fa fa-money"></i> RABAIS</h4>
                {!! Form::open(array('url' => 'pubdroit/cart/applyCoupon')) !!}
                    <p>Entrer votre code</p>
                    <div class="input-group">
                        <input type="text" value="" name="coupon" id="inputDiscount" class="form-control" placeholder="">
                        <span class="input-group-btn">
                            <button class="more-btn" type="submit">Appliquer</button>
                        </span>
                    </div><!-- /input-group -->
                {!! Form::close() !!}
            </div>
            @endif
        </figure>
        <figure class="col-md-4"></figure>
        <figure class="col-md-4 price-total">
            <div class="cart-option-box cart-option-box-checkout">
                <table width="100%" border="0" cellpadding="10" class="total-payment">
                    @if(isset($coupon) && !empty($coupon) && $cartworker->showCoupon())
                        <tr>
                            <td width="55%" align="left">Rabais appliqué</td>
                            <td width="45%" align="right">
                                @if($coupon['type'] == 'shipping')
                                    <span class="text-muted">Frais de port offerts</span>
                                @else
                                    @if($coupon['type'] == 'priceshipping')
                                        <span class="text-muted">Frais de port offerts</span><br/>
                                    @endif
                                    <span class="text-muted">{{ $coupon['title'] }}</span>
                                    &nbsp;{{ $coupon['value'] }} {{ $coupon['type']== 'price' || $coupon['type'] == 'priceshipping' ? 'CHF Rabais' : '%' }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td align="left"><h4>TOTAL</h4></td>
                        <td align="right"><h4>{{ number_format((float)$total, 2, '.', '') }} CHF</h4></td>
                    </tr>
                </table>
                <p class="text-right">
                    <a href="{{ url('pubdroit/checkout/billing') }}" id="btn-next-checkout" class="more-btn">Continuer &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
                </p>
            </div>
        </figure>
    </div>
@endif