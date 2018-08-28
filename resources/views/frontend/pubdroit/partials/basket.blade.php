<div class="cart-price">
    @if($product->url)
        <a class="cart-btn2" target="_blank" href="{{ $product->url }}">Commander</a>
        <span class="price">{{ $product->price_cents }} CHF</span>
    @else
        @if($product->sku > 0)
            <form method="post" action="{{ url('pubdroit/cart/addProduct') }}" class="form-inline">{!! csrf_field() !!}
                <button type="submit" id="btn_add_cart" class="cart-btn2">Ajouter au panier</button>
                @if($product->coupon_global_price)
                    <span class="price"><strike>{{ $product->price_cents }} CHF</strike></span>
                    <span class="price_rabais">{{ $product->coupon_global_price }} CHF</span>
                @else
                    <span class="price">{{ $product->price_cents }} CHF</span>
                @endif
                <input type="hidden" name="product_id" value="{{ $product->id }}">
            </form>
            <small><i>Prix public recommandé</i></small>
        @else
            <p class="text-muted">Ouvrage épuisé</p>
        @endif
    @endif
</div>