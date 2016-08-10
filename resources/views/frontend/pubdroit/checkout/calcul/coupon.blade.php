@if(isset($coupon) && !empty($coupon))
    <tr>
        <td colspan="2"></td>
        <td class="text-right"><strong>Rabais appliqué</strong></td>
        <td class="text-right">
            @if($coupon['type'] == 'shipping')
                <span class="text-muted">Frais de port offerts</span>
            @else
                <span class="text-muted">{{ $coupon['title'] }}</span> &nbsp;{{ $coupon['value'] }}%
            @endif
        </td>
    </tr>
@endif