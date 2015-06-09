@if(!Cart::content()->isEmpty())

    <div class="row" id="checkout">
        @foreach(Cart::content() as $item)
        <div class="col-md-2">
            <figure>
                <img src="{{ asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                <figcaption class="sp-quantity">
                    {!! Form::open(array('url' => 'quantityProduct', 'class' => 'form-inline')) !!}
                    {!! Form::hidden('_token', csrf_token()) !!}
                    <span class="sp-minus"><button class="ddd badge" data-multi="-1" type="submit">-</button></span>
                    <span class="sp-plus"><button class="ddd badge" data-multi="1" type="submit">+</button></span>
                    <input type="hidden" class="quntity-input" name="qty" value="{{ $item->qty }}">
                    {!! Form::hidden('rowid', $item->rowid) !!}
                    {!! Form::close() !!}
                </figcaption>
            </figure>
        </div>
        @endforeach
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-6"><span class="text-muted">Votre commande</span>&nbsp; {{ Cart::count() }} {{ Cart::count() > 1 ? 'articles': 'article'}}</div>
        <div class="col-md-6 text-right"><span class="text-muted">Total</span>&nbsp; {{ Cart::total() }} CHF</div>
    </div>

@endif