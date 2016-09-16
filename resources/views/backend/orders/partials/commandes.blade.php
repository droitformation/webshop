@if(!$orders->isEmpty())

    <?php $orders->load('products','shipping','coupon','payement'); ?>
    <?php $orders = $orders->sortByDesc('created_at'); ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2">Commande n°</th>
                <th class="col-md-2">Client</th>
                <th class="col-md-2">Date</th>
                <th class="col-md-1">Montant</th>
                <th class="col-md-1">Facture</th>
                <th class="col-md-2">Payé le</th>
                <th class="col-md-1">Via admin</th>
                <th class="text-right col-md-1"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr class="mainRow">
                <td>
                    <a class="btn btn-sky btn-xs" href="{{ url('admin/order/'.$order->id) }}"><i class="fa fa-edit"></i></a>&nbsp;
                    <a class="collapse_anchor" data-toggle="collapse" href="#order_no_{{ $order->id }}"><i class="fa fa-order fa-arrow-circle-right"></i>{{ $order->order_no }}</a>
                </td>
                <td>{{ $order->order_adresse ? $order->order_adresse->name : 'Admin' }}</td>
                <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                <td>{{ $order->total_with_shipping }} CHF</td>
                <td>

                    @if($order->facture)
                        <a target="_blank" href="{{ $order->facture }}?{{ rand(1,10000) }}" class="btn btn-xs btn-default">Facture en pdf</a>
                    @else
                        <form action="{{ url('admin/order/generate') }}" method="POST">
                            <input type="hidden" name="id" value="{{ $order->id }}">{!! csrf_field() !!}
                            <button class="btn btn-default btn-sm">Générer</button>
                        </form>
                    @endif

                </td>
                <td>

                    @if($cancelled)
                        {{ $order->payed_at ? $order->payed_at->format('Y-m-d') : '' }}&nbsp;
                        <span class="label label-{{ $order->payed_at ? 'success' : '' }}">{{ $order->payed_at ? 'payé' : 'en attente' }}</span>
                    @else
                        <div class="input-group">
                            <div class="form-control editablePayementDate"
                                 data-name="payed_at" data-type="date" data-pk="{{ $order->id }}"
                                 data-url="admin/order/edit" data-title="Date de payment">
                                {{ $order->payed_at ? $order->payed_at->format('Y-m-d') : '' }}
                            </div>
                            <span class="input-group-addon bg-{{ $order->payed_at ? 'success' : '' }}">
                                {{ $order->payed_at ? 'payé' : 'en attente' }}
                            </span>
                        </div>
                    @endif

                </td>
                <td>{!! $order->admin ? '<i class="fa fa-check"></i>' : '' !!}</td>
                <td class="text-right">

                    @if($cancelled)
                        <form action="{{ url('admin/order/restore/'.$order->id) }}" method="POST" class="form-horizontal">
                            <input type="hidden" name="id" value="{{ $order->id }}">{!! csrf_field() !!}
                            <button data-what="Restaurer" data-action="{{ $order->order_no }}" class="btn btn-warning btn-sm deleteAction">Restaurer</button>
                        </form>
                    @else
                        <form action="{{ url('admin/order/'.$order->id) }}" method="POST" class="form-horizontal">
                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                            <button data-what="Annuler" data-action="{{ $order->order_no }}" class="btn btn-danger btn-sm deleteAction">Annuler</button>
                        </form>
                    @endif

                </td>
            </tr>
            @if(!empty($order->products))

                <?php
                    $grouped = $order->products->groupBy(function ($item, $key) {
                        return $item->id.$item->pivot->price.$item->pivot->rabais.$item->pivot->isFree;
                    });
                ?>
                <tr>
                    <td colspan="9" class="nopadding">
                        <div class="collapse customCollapse" id="order_no_{{ $order->id }}">
                            <!-- Details of order -->
                            <div class="inscription_wrapper">
                                <div class="row">
                                    <div class="col-md-3">
                                        <address class="well well-sm">

                                           <?php $adresse = $order->order_adresse ?>

                                           @if($adresse)
                                               <strong>{{ $adresse->civilite_title }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
                                               {!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
                                               {{ $adresse->adresse }}<br>
                                               {!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
                                               {!! !empty($adresse->cp) ? $adresse->cp.'<br>' : '' !!}
                                               {{ $adresse->npa }} {{ $adresse->ville }}<br>
                                               {{ $adresse->pays_title }}
                                           @endif
                                        </address>
                                    </div>
                                    <div class="col-md-9">
                                        <table width="100%" class="table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Qt</th>
                                                    <th>Titre</th>
                                                    <th class="text-right">Prix unité</th>
                                                    <th class="text-right">Prix spécial</th>
                                                    <th class="text-right">Prix</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($grouped as $product)

                                                <?php  $money     = new \App\Droit\Shop\Product\Entities\Money; ?>
                                                <?php  $price_sum = $product->reject(function ($item) { return $item->pivot->isFree; })->sum('price_cents'); ?>

                                                <tr>
                                                    <td width="10%" valign="top"><p class="text-left" style="width:70px;margin-right: 20px;">{{ $product->count() }} x</p></td>
                                                    <td width="40%" valign="top">
                                                        <a href="{{ url('admin/product/'.$product->first()->id) }}">
                                                            {{ $product->first()->title }} {!! ($product->first()->isbn ? '&nbsp;<small>(ISBN: '.$product->first()->isbn.')</small>' : '') !!}
                                                        </a>
                                                    </td>
                                                    <td width="18%" valign="top" class="text-right">{{ $product->first()->price_normal }} CHF</td>
                                                    <td width="18%" valign="top" class="text-right">
                                                        {{ $product->first()->price_special ? $product->first()->price_special.' CHF' : '' }}
                                                    </td>
                                                    <td width="19%" valign="top"><p class="text-right">{{ $price_sum > 0 ? $money->format($price_sum).' CHF' : 'Gratuit' }} </p></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br/>
                                    <table width="100%" class="table-condensed">
                                        <tbody>
                                            <tr>
                                                <td width="85%" class="text-right">Payement</td>
                                                <td width="15%" class="text-right">{{ $order->payement->title }}</td>
                                            </tr>
                                            <tr>
                                                <td width="85%" class="text-right">
                                                    @if(isset($order->coupon))
                                                        <strong>Rabais appliqué<small class="text-muted">{!! $order->coupon->coupon_title !!}</small></strong>
                                                    @endif
                                                </td>
                                                <td width="15%" class="text-right">
                                                    @if($order->coupon_id > 0)
                                                        <?php $order->load('coupon'); ?>
                                                        <p class="text-muted">{!! $order->coupon->coupon_value !!}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="85%" class="text-right">Frais de port</td>
                                                <td width="15%" class="text-right">{{ $order->shipping->price_cents }} CHF</td>
                                            </tr>
                                            <tr><td colspan="2" style="line-height: 10px;">&nbsp;</td></tr>
                                            <tr>
                                                <td width="85%" style="padding-top:5px;" class="text-right"><strong>Total</strong></td>
                                                <td width="15%" style="border-top:1px solid #ddd;padding-top:5px;"><p class="text-right">{{ $order->total_with_shipping }} CHF</p></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                            <!-- end details of order -->

                        </div>
                    </td>
                </tr>
            @endif

        @endforeach
        </tbody>
    </table>
@else
    <p>Encore aucune commandes</p>
@endif