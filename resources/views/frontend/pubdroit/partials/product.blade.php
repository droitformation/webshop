<figure class="s-product">
    @if($news)
        <span class="sale-icon">Sale</span>
    @endif
    <div class="row">
        <div class="col-md-4">
            <a href="{{ url('product/'.$product->id) }}">
                <img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
            </a>
        </div>
        <article class="col-md-8">
            <h3><a href="{{ asset('files/products/'.$product->image) }}">{{ $product->title }}</a></h3>
            <p>{!! $product->teaser !!}</p>
            <div class="cart-price">
                <form method="post" action="{{ url('cart/addProduct') }}" class="form-inline">{!! csrf_field() !!}
                    <button type="submit" class="cart-btn2">Ajouter au panier</button>
                    <span class="price">{{ $product->price_cents }} CHF</span>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                </form>
            </div>
        </article>
    </div>
</figure>