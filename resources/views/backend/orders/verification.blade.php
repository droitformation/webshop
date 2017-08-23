@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                        <div class="row">
                            <div class="col-md-4">
                                <h3>Adresse</h3>
                                <div class="well user-adresse">
                                    @include('templates.partials.adresse', ['adresse' => $preview->adresse()])
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h3>Configurations</h3>
                                <dl class="">

                                    <dt>Frais de port</dt>
                                    <dd>{{ $preview->shipping() }}</dd>
                                    <dd>{{ $preview->paquet() }}</dd>

                                    @if(!empty( $preview->messages() ))
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
                                    <th>Quantité</th>
                                    <th>Rabais</th>
                                    <th>Prix spécial</th>
                                    <th>Livre gratuit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview->products() as $product)
                                    <tr>
                                        <td><strong>{{ $product['product']->title }}</strong></td>
                                        <td>{{ $product['qty'] }}</td>
                                        <td>{{ $product['rabais'] }}</td>
                                        <td>{{ $product['price'] }}</td>
                                        <td>{{ $product['gratuit'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>

                        <form action="{{ url('admin/order/correction') }}" method="POST" class="pull-left">{!! csrf_field() !!}
                            <input name="data" value="{{ json_encode($data) }}" type="hidden">
                            <button type="submit" class="btn btn-default">Retour</button>
                        </form>

                        <form action="{{ url('admin/order') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                            <input name="data" value="{{ json_encode($data) }}" type="hidden">
                            <button type="submit" class="btn btn-success">Valider la commande</button>
                        </form>

                </div>
            </div>

        </div>
    </div>

@stop