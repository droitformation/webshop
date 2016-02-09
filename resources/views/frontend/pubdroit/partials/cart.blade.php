@if(!Cart::content()->isEmpty())

    <?php $cart  = Cart::content(); ?>
    <div class="cart-table-holder">
        <table width="100%" border="0" cellpadding="10">
            <tr>
                <th width="47%" align="left" colspan="2">Ouvrage</th>
                <th width="15%" style="text-align: center;">Prix par unité</th>
                <th width="12%" style="text-align: center;">Quantité</th>
                <th width="15%" style="text-align: right;">Sous-total</th>
                <th width="5%" class="mobile-hidden">&nbsp;</th>
            </tr>
            @foreach($cart as $item)
            <tr bgcolor="#FFFFFF" class="product-detail">
                <td valign="top" class="mobile-hidden" align="center">
                    <img style="max-height:80px;" src="{{ asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                </td>
                <td valign="top">{{ $item->name }}</td>
                <td align="center" valign="top">{{ $item->price }} CHF</td>
                <td align="center" valign="top">
                    <form method="post" action="{{ url('cart/quantityProduct') }}" class="form-inline">{!! csrf_field() !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="qty" value="{{ $item->qty }}">
                            <span class="input-group-btn">
                               <button class="btn btn-default btn-sm" type="submit">éditer</button>
                            </span>
                        </div><!-- /input-group -->
                        <input type="hidden" name="rowid" value="{{ $item->rowid }}">
                    </form>
                </td>
                <td align="right" valign="top">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                <td align="center" valign="top" class="mobile-hidden">
                    <form method="post" action="{{ url('cart/quantityProduct') }}" class="form-inline">
                        <input type="hidden" name="qty" value="0">
                        <input type="hidden" name="rowid" value="{{ $item->rowid }}">
                        <button type="submit"><i class="icon-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

@endif