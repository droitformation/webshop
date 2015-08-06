@extends('layouts.user')
@section('content')


<div class="col-md-9 content">

    <div class="panel panel-default">
        <div class="panel-heading">Commandes</div>
        <div class="panel-body">
            <?php
                setlocale(LC_ALL, 'fr_FR.UTF-8');
                $user->orders->load('products','shipping','coupon');
            ?>
            @if(!$user->orders->isEmpty())
                <table class="table order-list">
                    <tr>
                        <th>Commande n°</th>
                        <th>Passée le </th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                    @foreach($user->orders as $order)

                    <tr>
                        <td>{{ $order->order_no }}</td>
                        <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                        <td>{{ $order->price_cents }}</td>
                        <td class="text-right">
                            <a data-toggle="collapse" href="#order_no_{{ $order->id }}" aria-expanded="false" aria-controls="order_no_{{ $order->id }}">Voir la commande</a>
                        </td>
                    </tr>

                    @if(!empty($order->products))

                        <?php $grouped = $order->products->groupBy('id'); ?>
                        <tr>
                            <td colspan="4" class="nopadding">

                                <div class="collapse" id="order_no_{{ $order->id }}">
                                    <div class="well">
                                    @foreach($grouped as $product)
                                        <div class="row order-item">
                                            <div class="col-md-1">
                                                <a href="#"><img height="40" src="{{ asset('files/products/'.$product->first()->image) }}" alt=""></a>
                                            </div>
                                            <div class="col-md-8">{{ $product->first()->title }}</div>
                                            <div class="col-md-1"><p class="text-right">{{ $product->count() }} x</p></div>
                                            <div class="col-md-2"><p class="text-right">{{ $product->first()->price_cents }} CHF</p></div>
                                        </div>
                                    @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            @if(isset($order->coupon))
                                               <p class="text-right"><strong>Rabais appliqué <small class="text-muted">{{ $order->coupon->title }}</small></strong></p>
                                            @endif
                                            <p class="text-right">Frais de port:</p>
                                            <p class="text-right"><strong>Total:</strong></p>
                                        </div>
                                        <div class="col-md-3">

                                            @if($order->coupon_id > 0)
                                                @if( $order->coupon->type == 'shipping')
                                                    <?php $order->coupon_id->load('coupon'); ?>
                                                    <p class="text-right text-muted">Frais de port offerts</p>
                                                @else
                                                    <p class="text-right">- {{ $order->coupon->value }}%</p>
                                                @endif
                                            @endif
                                            <p class="text-right">{{ $order->shipping->price_cents }} CHF</p>
                                            <p class="text-right">{{ $order->price_cents }} CHF</p>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @endif

                @endforeach
                </table>
            @else
                <p>Encore aucune commandes</p>
            @endif

        </div><!-- end panel body -->
    </div><!-- end panel -->

</div>


@endsection
