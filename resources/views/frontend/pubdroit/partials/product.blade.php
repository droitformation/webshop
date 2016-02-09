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
                {!! Form::open(array('url' => 'cart/addProduct')) !!}
                {!! Form::hidden('_token', csrf_token()) !!}
                <button type="submit" class="cart-btn2">Ajouter au panier</button>
                <span class="price">{{ $product->price_cents }} CHF</span>
                {!! Form::hidden('product_id', $product->id) !!}
                {!! Form::close() !!}
            </div>
        </article>
    </div>
</figure>