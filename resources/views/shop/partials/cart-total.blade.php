@if(!Cart::content()->isEmpty())

    <hr/>
    <div class="row">
        <div class="col-md-6"><span class="text-muted">Votre commande</span>&nbsp; {{ Cart::count() }} {{ Cart::count() > 1 ? 'articles': 'article'}}</div>
        <div class="col-md-6 text-right">
            @if(isset($coupon) && !empty($coupon))
                <p>Rabais appliqué
                    @if($coupon['type'] == 'shipping')
                        <span class="text-muted">Frais de port offerts</span>
                    @else
                        <span class="text-muted">{{ $coupon['title'] }}</span> &nbsp;{{ $coupon['value'] }}%
                    @endif
                </p>
            @endif
            <strong>Total</span>&nbsp;
                {{ number_format((float)Cart::total(), 2, '.', '') }} CHF
            </strong>
        </div>
    </div>

@endif