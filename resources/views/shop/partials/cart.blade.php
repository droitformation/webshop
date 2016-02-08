@if(!Cart::content()->isEmpty())

    <?php $cart  = Cart::content(); ?>
    <div class="cart-table-holder">
        <table width="100%" border="0" cellpadding="10">
            <tr>
                <th width="14%" class="mobile-hidden">&nbsp;</th>
                <th width="43%" align="left">Nom</th>
                <th width="6%" class="mobile-hidden"></th>
                <th width="10%">Prix par unité</th>
                <th width="10%">Quantité</th>
                <th width="12%">Sous-total</th>
                <th width="5%" class="mobile-hidden">&nbsp;</th>
            </tr>
            @foreach($cart as $item)
            <tr bgcolor="#FFFFFF" class="product-detail">
                <td valign="top" class="mobile-hidden">
                    <img src="{{ asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                </td>
                <td valign="top">{{ $item->title }}</td>
                <td align="center" valign="top" class="mobile-hidden"><a href="#">Edit</a></td>
                <td align="center" valign="top">{{ $item->price_cents }}</td>
                <td align="center" valign="top"><input name="" type="text" value="1" /></td>
                <td align="center" valign="top">{{ $item->price_cents }}</td>
                <td align="center" valign="top" class="mobile-hidden"><a href="#"> <i class="icon-trash"></i></a></td>
            </tr>
            @endforeach
        </table>
    </div>

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

@endif