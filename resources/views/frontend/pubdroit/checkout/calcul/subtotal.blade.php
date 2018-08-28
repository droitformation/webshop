@inject('cart_worker', 'App\Droit\Shop\Cart\Worker\CartWorker')

<tr>
    <td colspan="4" align="right">
        <p>Sous-total</p>
        <p>Frais d'envoi</p>
        <p><strong>Total</strong></p>
    </td>
    <td align="right">
        <p>{{ $cart_worker->totalCart() }} CHF</p>
        <p>{{ $cart_worker->totalShipping() }} CHF</p>
        <p><strong>{{ $cart_worker->totalCartWithShipping() }} CHF</strong></p>
    </td>
</tr>