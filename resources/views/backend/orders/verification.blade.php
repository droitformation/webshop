@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                        <h3 class="text-info">Vérifier les données de la commande</h3>

                        <div class="row">
                            <div class="col-md-4">
                                <h3>Adresse</h3>
                                <div class="well user-adresse">
                                    @include('templates.partials.adresse', ['adresse' => $preview->adresse()])
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h3>Configurations</h3>
                                <dl class="config-commande">

                                    <dt>Frais de port</dt>
                                    <dd>{{ $preview->shipping() }}</dd>
                                    <dd>{{ $preview->paquet() }}</dd>

                                    @if($preview->messages())
                                        <dt>Messages</dt>
                                        <dd>
                                            @foreach($preview->messages() as $color => $message)
                                                <span class="message {{ $color }}">{{ $message }}</span>
                                            @endforeach
                                        </dd>
                                    @endif

                                </dl>

                            </div>
                        </div>

                        <h3>Commande</h3>
                        <table class="table">
                            <thead>
                                <tr class="active">
                                    <th>Titre</th>
                                    <th class="text-right">Rabais</th>
                                    <th class="text-right">Prix spécial</th>
                                    <th class="text-right">Livre gratuit</th>
                                    <th class="text-right">Quantité</th>
                                    <th class="text-right">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview->products() as $product)
                                    <tr>
                                        <td><strong>{{ $product['product']->title }}</strong></td>
                                        <td class="text-right">{{ $product['rabais'] }}</td>
                                        <td class="text-right">{{ $product['price'] }}</td>
                                        <td class="text-right">{{ $product['gratuit'] }}</td>
                                        <td class="text-right">{{ $product['qty'] }}</td>
                                        <td class="text-right">{{ $product['prix'] }}</td>
                                    </tr>
                                @endforeach
                                <tr><td colspan="6">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="4" class="text-right no-border"></td>
                                    <td class="text-right no-border">Frais de port</td>
                                    <td class="text-right no-border">{{ $preview->shipping_total() }} CHF</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right mt-10 pb-0 no-border"></td>
                                    <td class="text-right" style="width: 110px; border-color: #999;"><strong>Total</strong></td>
                                    <td class="text-right" style="border-color: #999;"">{{ $preview->order_total() }} CHF</td>
                                </tr>
                            </tbody>

                        </table>

                        <div class="btn-order">
                            <form action="{{ url('admin/order/correction') }}" method="POST" class="pull-left">{!! csrf_field() !!}
                                <input name="data" value="{{ json_encode($data) }}" type="hidden">
                                <button id="returnOrderPage" type="submit" class="btn btn-default">Retour</button>
                            </form>

                            <form action="{{ url('admin/order') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                <input name="data" value="{{ json_encode($data) }}" type="hidden">
                                <button type="submit" class="btn btn-success">Valider la commande</button>
                            </form>
                        </div>

                </div>
            </div>

        </div>
    </div>

@stop