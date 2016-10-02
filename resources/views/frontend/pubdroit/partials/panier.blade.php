<div class="checkout">
    <a class="checkout__button" href="{{ url('pubdroit/checkout/cart') }}"><!-- Fallback location -->
        <span class="checkout__text">
            @inject('cart_worker', 'App\Droit\Shop\Cart\Worker\CartWorker')

            <span class="checkout__articles">
                <i class="fa fa-shopping-cart"></i>
            @if($cart_worker->countCart() > 0)
                {{ $cart_worker->countCart() }} {{ $cart_worker->countCart() > 1 ? 'articles': 'article'}}  {{ $cart_worker->totalCart() }} CHF
            @else
                0 article(s)  0.00 CHF
            @endif
            </span>
        </span>
    </a>
</div><!-- /checkout -->
