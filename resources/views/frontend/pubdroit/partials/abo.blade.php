<figure class="s-product">
    @if(isset($news))
        <span class="sale-icon">Sale</span>
    @endif
    <div class="row">
        <div class="col-md-4">
            <a href="{{ url('pubdroit/product/'.$product->id) }}">
                <img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
            </a>
        </div>
        <article class="col-md-8">

            @foreach($product->abos as $abo)
                <h3><a href="{{ url('pubdroit/product/'.$product->id) }}">Abonnement {{ $abo->title }}</a></h3>
                <p>{{ $abo->plan_fr }}</p>
                <p><strong>Dernière édition:</strong> <br/><i>{{ $product->title }}</i></p>

                <form method="post" action="{{ url('pubdroit/cart/addAbo') }}" class="form-inline">{!! csrf_field() !!}
                    <button type="submit" id="addAbo_{{ $abo->id }}" class="cart-btn2">Ajouter au panier</button>
                    <span class="price">{{ $abo->price_cents }} CHF</span>
                    <input type="hidden" name="abo_id" value="{{ $abo->id }}">
                </form>
            @endforeach

        </article>
    </div>
</figure>