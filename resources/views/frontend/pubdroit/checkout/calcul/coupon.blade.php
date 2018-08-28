
@inject('cartworker','App\Droit\Shop\Cart\Worker\CartWorker')

@if(isset($coupon) && !empty($coupon) && $cartworker->showCoupon())
    <tr>
        <td colspan="3"></td>
        <td class="text-right"><strong>Rabais appliqué</strong></td>
        <td class="text-right">
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