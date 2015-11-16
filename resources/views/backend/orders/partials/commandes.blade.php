@if(!$orders->isEmpty())

    <?php $orders->load('products','shipping','coupon','payement'); ?>
    <?php $orders = $orders->sortByDesc('created_at'); ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Commande n°</th>
                <th>Par</th>
                <th>Date</th>
                <th>Payé le</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Statut</th>
                <th class="text-right">Facture</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr class="mainRow">
                <td>
                    <a class="collapse_anchor" data-toggle="collapse" href="#order_no_{{ $order->id }}">
                        <i class="fa fa-arrow-circle-right"></i>
                        {{ $order->order_no }}
                    </a>
                </td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                <td>{{ $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '' }}</td>
                <td class="text-right">{{ $order->price_cents }} CHF</td>
                <td class="text-right"><span class="label label-{{ $order->status_code['color'] }}">{{ $order->status_code['status'] }}</span></td>
                <td class="text-right"><?php echo ($order->facture ? '<a target="_blank" href="'.$order->facture.'" class="btn btn-xs btn-default">Facture en pdf</a>' : ''); ?></td>
            </tr>
            @if(!empty($order->products))

                <?php $grouped = $order->products->groupBy('id'); ?>
                <tr>
                    <td colspan="7" class="nopadding">
                        <div class="collapse customCollapse" id="order_no_{{ $order->id }}">
                            <div class="inscription_wrapper">

                                <div class="row">
                                    <div class="col-md-3">
                                        <address class="well well-sm">
                                           <?php $order->user->load('adresses'); ?>
                                            @include('shop.partials.user-livraison', ['user' => $order->user])
                                        </address>
                                    </div>
                                    <div class="col-md-9">
                                        <table width="100%" class="table-condensed">
                                            <thead>
                                                <tr><th>Qt</th><th>Titre</th><th class="text-right">Prix</th></tr>
                                                <tr><th colspan="3" style="border-top:1px solid #bebebe;line-height: 6px;padding:0;">&nbsp;</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($grouped as $product)
                                                <tr>
                                                    <td width="10%"><p class="text-left" style="width:70px;margin-right: 20px;">{{ $product->count() }} x</p></td>
                                                    <td width="75%"><a href="{{ url('admin/product/'.$product->first()->id) }}">{{ $product->first()->title }}</a></td>
                                                    <td width="15%"><p class="text-right">{{ $product->first()->price_cents }} CHF</p></td>
                                                </tr>
                                            @endforeach
                                            <tr><td colspan="3" style="line-height: 9px;">&nbsp;</td></tr>
                                            <tr>
                                                <td width="85%" colspan="2"><p class="text-right">Payement</p></td>
                                                <td width="15%"><p class="text-right">{{ $order->payement->title }}</p></td>
                                            </tr>
                                            <tr>
                                                <td width="10%"></td>
                                                <td width="75%" class="text-right">
                                                    @if(isset($order->coupon))<strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong>@endif
                                                </td>
                                                <td width="15%">
                                                    @if($order->coupon_id > 0)
                                                        <?php $order->load('coupon'); ?>
                                                        <p class="text-right text-muted"><?php echo ($order->coupon->type == 'shipping' ? 'Frais de port offerts' : $order->coupon->value.'%') ?></p>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="85%" colspan="2"><p class="text-right">Frais de port</p></td>
                                                <td width="15%"><p class="text-right">{{ $order->shipping->price_cents }} CHF</p></td>
                                            </tr>
                                            <tr><td colspan="3" style="line-height: 9px;">&nbsp;</td></tr>
                                            <tr>
                                                <td width="85%" colspan="2" style="padding-top:5px;" class="text-right"><strong>Total</strong></td>
                                                <td width="15%" style="border-top:1px solid #ddd;padding-top:5px;"><p class="text-right">{{ $order->price_cents }} CHF</p></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

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