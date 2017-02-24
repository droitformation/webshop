<figure class="s-product">
    @if(isset($news))
        <span class="sale-icon">Sale</span>
    @endif
    <div class="row">
        <div class="col-md-4">
            <a href="{{ url('pubdroit/product/'.$product->id) }}">
                <img src="{{ secure_asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
            </a>
        </div>
        <article class="col-md-8">

            <h3><a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a></h3>
            <p>{!! $product->teaser !!}</p>

            <!-- Product put in the basket button -->
            @include('frontend.pubdroit.partials.basket')
            <!-- END Product put in the basket button -->
        </article>
    </div>
</figure>