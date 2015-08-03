@if(!Cart::content()->isEmpty())

    <div id="checkout">

        <?php
            $cart  = Cart::content();
            $chunk = $cart->chunk(6);
        ?>
        @foreach($chunk as $row)
            <div class="row">
                @foreach($row as $item)
                    <div class="col-md-2">
                        <figure>
                            <img src="{{ asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                            <figcaption class="sp-quantity">
                                {!! Form::open(array('url' => 'cart/quantityProduct', 'class' => 'form-inline')) !!}
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
        @endforeach

    </div>

@endif