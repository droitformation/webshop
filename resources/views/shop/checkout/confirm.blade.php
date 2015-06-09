@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Confirmation de la commande</h2>
    </div>
</div>

<!-- Cart  -->
@include('shop.partials.cart')

@if(!Cart::content()->isEmpty())
<div class="row" id="panier">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th width="60%">Titre</th>
                    <th class="text-right" width="25%">Quantité</th>
                    <th class="text-right" width="15%">Prix</th>
                </tr>
            </thead>
            @foreach(Cart::content() as $item)
            <tbody>
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">{{ $item->price }} CHF</td>
                </tr>
            </tbody>
            @endforeach
            <tfoot>
                <tr class="active">
                    <td></td>
                    <td class="text-right"><strong>Total des produits</strong></td>
                    <td class="text-right">{{ Cart::total() }} CHF</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

<ul class="pager">
    <li class="next next-commander"><a href="{{ url('checkout/confirm') }}">Envoyer la commande &nbsp;<span class="glyphicon glyphicon-ok"></span></a></li>
</ul>

@stop