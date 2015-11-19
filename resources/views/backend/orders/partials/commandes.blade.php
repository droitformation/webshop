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
                <td>{{ $order->user->name or $order->adresse->name }}</td>
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
                                           <?php
                                                if($order->user_id)
                                                {
                                                    $order->user->load('adresses');
                                                    $order->user->adresse_livraison->load(['pays','civilite']);
                                                    $adresse = $order->user->adresse_livraison;
                                                }
                                                else{
                                                    $adresse = $order->adresse;
                                                    $adresse->load(['pays','civilite']);
                                                }
                                           ?>
                                            
                                           <strong>{{ $adresse->civilite->title }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
                                           {!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
                                           {{ $adresse->adresse }}<br>
                                           {!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
                                           {!! !empty($adresse->cp) ? $adresse->cp.'<br>' : '' !!}
                                           {{ $adresse->npa }} {{ $adresse->ville }}<br>
                                           {{ $adresse->pays->title }}
                                            
                                        </address>
                                    </div>
                                    <div class="col-md-9">
                                        <table width="100%" class="table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Qt</th>
                                                    <th>Titre</th>
                                                    <th class="text-right">Prix unité</th>
                                                    <th class="text-right">Prix</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($grouped as $product)
                                                <?php
                                                    // Is the product free?
                                                    $price_sum = $product->reject(function ($item) {
                                                        return $item->pivot->isFree;
                                                    })->sum('price_cents');

                                                    $prod_free = $product->filter(function ($item) {
                                                        return $item->pivot->isFree;
                                                    });
                                                ?>
                                                <tr>
                                                    <td width="10%" valign="top"><p class="text-left" style="width:70px;margin-right: 20px;">{{ $product->count() }} x</p></td>
                                                    <td width=60%" valign="top">
                                                        <a href="{{ url('admin/product/'.$product->first()->id) }}">{{ $product->first()->title }}</a>
                                                        @if(!$prod_free->isEmpty())
                                                            <br/><small>Dont livres gratuits : {{ $prod_free->count() }}</small>
                                                        @endif
                                                    </td>
                                                    <td width="15%" valign="top" class="text-right">
                                                        {{ $product->first()->price_cents }} CHF
                                                    </td>
                                                    <td width="15%" valign="top"><p class="text-right">{{ $price_sum }} CHF</p></td>
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
                                                    @if(isset($order->coupon))<strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong>@endif
                                                </td>
                                                <td width="15%" class="text-right">
                                                    @if($order->coupon_id > 0)
                                                        <?php $order->load('coupon'); ?>
                                                        <p class="text-muted"><?php echo ($order->coupon->type == 'shipping' ? 'Frais de port offerts' : $order->coupon->value.'%') ?></p>
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