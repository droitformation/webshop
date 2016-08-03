<figure class="s-product">
    @if(isset($news))
        <span class="sale-icon">Sale</span>
    @endif
    <div class="row">
        <div class="col-md-4">
            <a href="{{ url('product/'.$product->id) }}">
                <img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
            </a>
        </div>
        <article class="col-md-8">

            @foreach($product->abos as $abo)
                <h3><a href="{{ url('product/'.$product->id) }}">Abonnement {{ $abo->title }}</a></h3>
                <p>{{ $abo->plan_fr }}</p>
                <p><strong>Dernière édition:</strong> <br/><i>{{ $product->title }}</i></p>
            @endforeach

            <!-- Product put in the basket button -->
            @include('frontend.pubdroit.partials.basket')
            <!-- END Product put in the basket button -->
        </article>
    </div>
</figure>