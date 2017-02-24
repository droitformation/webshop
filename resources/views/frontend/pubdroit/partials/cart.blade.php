@if(!Cart::instance('shop')->content()->isEmpty())

    <?php $cart = Cart::instance('shop')->content(); ?>
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
                    <img style="max-height:80px;" src="{{ secure_asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                </td>
                <td valign="middle">{{ $item->name }}</td>
                <td align="center" valign="middle">{{ $item->model->price_cents }} CHF</td>
                <td align="center" valign="middle">
                    <form method="post" action="{{ url('pubdroit/cart/quantityProduct') }}" class="form-inline">
                        {!! csrf_field() !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="qty" value="{{ $item->qty }}">
                            <span class="input-group-btn">
                               <button class="btn btn-default btn-sm" type="submit">éditer</button>
                            </span>
                        </div><!-- /input-group -->
                        <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                    </form>
                </td>
                <td align="right" valign="middle">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                <td align="center" valign="middle" class="mobile-hidden">
                    <form method="post" action="{{ url('pubdroit/cart/removeProduct') }}" class="form-inline">{!! csrf_field() !!}
                        <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                        <button id="removeProduct_{{ $item->id }}" type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

@endif

@if( !\Cart::instance('abonnement')->content()->isEmpty() )

    <?php $abos = Cart::instance('abonnement')->content(); ?>
    <div class="cart-table-holder cart-table-holder-abo">
        <table width="100%" border="0" cellpadding="10">
            <tr>
                <th width="15%" align="left">Abonnement</th>
                <th width="32%" align="left"></th>
                <th width="15%" style="text-align: center;">Prix</th>
                <th width="12%" style="text-align: center;">Quantité</th>
                <th width="15%" style="text-align: right;">Sous-total</th>
                <th width="5%" class="mobile-hidden">&nbsp;</th>
            </tr>
            @foreach($abos as $item)
                <tr bgcolor="#FFFFFF" class="product-detail">
                    <td valign="middle" align="left">
                        <img src="{{ secure_asset('files/main/'.$item->options->image) }}" />
                    </td>
                    <td valign="middle" class="text-left">
                        <p>Demande d'abonnement <strong>{{ $item->name }}</strong></p>
                        {!! $item->model->remarque !!}
                    </td>
                    <td class="text-center" valign="middle">{{ $item->price }} CHF/{{ strtolower($item->options->plan) }}</td>
                    <td class="text-center" valign="middle">
                        <form method="post" action="{{ url('pubdroit/cart/quantityAbo') }}" class="form-inline">
                            {!! csrf_field() !!}
                            <div class="input-group">
                                <input type="text" class="form-control" name="qty" value="{{ $item->qty }}">
                                <span class="input-group-btn">
                                   <button class="btn btn-default btn-sm" type="submit">éditer</button>
                                </span>
                            </div><!-- /input-group -->
                            <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                        </form>
                    </td>
                    <td class="text-right" valign="middle">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                    <td class="text-center" valign="middle" class="mobile-hidden">
                        <form method="post" action="{{ url('pubdroit/cart/removeAbo') }}" class="form-inline">{!! csrf_field() !!}
                            <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                            <button id="removeProduct_{{ $item->id }}" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endif