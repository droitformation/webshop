@if(!$orders->isEmpty())

    <?php $orders->load('products','shipping','coupon','payement'); ?>
    <?php $orders = $orders->sortByDesc('created_at'); ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Commande n°</th>
                <th>Date</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Statut</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr class="mainRow">
                <td>
                    <a class="collapse_anchor" data-toggle="collapse" href="#order_no_{{ $order->id }}"><i class="fa fa-arrow-circle-right"></i>{{ $order->order_no }}</a>
                </td>
                <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                <td class="text-right">{{ $order->price_cents }} CHF</td>
                <td class="text-right"><span class="label label-{{ $order->status_code['color'] }}">{{ $order->status_code['status'] }}</span></td>
            </tr>
            @if(!empty($order->products))

                <?php $grouped = $order->products->groupBy('id'); ?>
                <tr>
                    <td colspan="4" class="nopadding">
                        <div class="collapse customCollapse" id="order_no_{{ $order->id }}">
                            <div class="inscription_wrapper">

                                @foreach($grouped as $product)
                                    <div class="row order-item">
                                        <div class="col-md-1">
                                            <a href="#"><img height="40" src="{{ secure_asset('files/products/'.$product->first()->image) }}" alt=""></a>
                                        </div>
                                        <div class="col-md-8">{{ $product->first()->title }}</div>
                                        <div class="col-md-1"><p class="text-right">{{ $product->count() }} x</p></div>
                                        <div class="col-md-2"><p class="text-right">{{ $product->first()->price_cents }} CHF</p></div>
                                    </div>
                                @endforeach

                                <div class="row">
                                    <div class="col-md-9"><p class="text-right">Payement</p></div>
                                    <div class="col-md-3"><p class="text-right">{{ $order->payement->title }}</p></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        @if($order->facture )
                                            <a target="_blank" href="{{ $order->facture }}" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> &nbsp;Facture en pdf</a>
                                        @endif
                                    </div>
                                    <div class="col-md-7">
                                        @if(isset($order->coupon))
                                            <p class="text-right"><strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong></p>
                                        @endif
                                        <p class="text-right">Frais de port</p>
                                        <p class="text-right"><strong>Total</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        @if($order->coupon_id > 0)
                                            <?php $order->load('coupon'); ?>
                                            <p class="text-right text-muted"><?php echo ($order->coupon->type == 'shipping' ? 'Frais de port offerts' : $order->coupon->value.'%') ?></p>
                                        @endif
                                        <p class="text-right">{{ $order->shipping->price_cents }} CHF</p>
                                        <p class="text-right">{{ $order->price_cents }} CHF</p>
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