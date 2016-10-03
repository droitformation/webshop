@extends('frontend.pubdroit.profil.index')
    @section('profil')
    @parent

            <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(!$user->orders->isEmpty())
            <?php $user->orders->load('products','shipping','coupon','payement'); ?>
            <div class="table-responsive">
                <table class="table order-list">
                    <tr>
                        <th>Commande n°</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Payement</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                    @foreach($user->orders as $order)

                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                            <td><span class="label label-{{ $order->send_at ? 'success' : 'default' }}">{{ $order->send_at ? 'Envoyé' : 'En attente' }}</span></td>
                            <td><span class="label label-{{ $order->status_code['color'] }}">{{ $order->status_code['status'] }}</span></td>
                            <td>{{ $order->total_with_shipping }}</td>
                            <td class="text-right">
                                <a class="text-info" data-toggle="collapse" href="#order_no_{{ $order->id }}" aria-expanded="false" aria-controls="order_no_{{ $order->id }}">Voir la commande</a>
                            </td>
                        </tr>

                        @if(!empty($order->products))

                            <?php $grouped = $order->products->groupBy('id'); ?>
                            <tr>
                                <td colspan="6" class="nopadding">

                                    <div class="collapse" id="order_no_{{ $order->id }}">
                                        <div id="orders-items">
                                            @foreach($grouped as $product)
                                                <div class="row order-item">
                                                    <div class="col-md-1">
                                                        <a href="{{ url('product/'.$product->first()->id) }}"><img height="35" src="{{ asset('files/products/'.$product->first()->image) }}" alt=""></a>
                                                    </div>
                                                    <div class="col-md-8">{{ $product->first()->title }}</div>
                                                    <div class="col-md-1"><p class="text-right">{{ $product->count() }} x</p></div>
                                                    <div class="col-md-2"><p class="text-right">{{ $product->first()->price_cents }} CHF</p></div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9"><p class="text-right">Payement</p></div>
                                            <div class="col-md-3"><p class="text-right">{{ $order->payement->title }}</p></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($order->facture)
                                                    <a target="_blank" href="{{ asset($order->facture) }}" class="btn btn-sm btn-default"><i class="fa fa-file"></i> &nbsp;Facture en pdf</a>
                                                @endif
                                            </div>
                                            <div class="col-md-5">
                                                @if(isset($order->coupon))
                                                    <p class="text-right"><strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong></p>
                                                @endif
                                                <p class="text-right">Frais de port</p>
                                                <p class="text-right"><strong>Total</strong></p>
                                            </div>
                                            <div class="col-md-3">

                                                @if($order->coupon_id > 0)
                                                    @if( $order->coupon->type == 'shipping')
                                                        <p class="text-right text-muted">Frais de port offerts</p>
                                                    @else
                                                        <p class="text-right">- {{ $order->coupon->value }}%</p>
                                                    @endif
                                                @endif
                                                <p class="text-right">{{ $order->shipping->price_cents }} CHF</p>
                                                <p class="text-right">{{ $order->total_with_shipping }} CHF</p>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endif

                    @endforeach
                </table>
            </div>
        @else
            <p>Encore aucune commandes</p>
        @endif
    </div>
@endsection
