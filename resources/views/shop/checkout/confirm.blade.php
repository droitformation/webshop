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
                    <td class="text-right">
                        {{ number_format((float)$item->price, 2, '.', '') }}
                    </td>
                </tr>
            </tbody>
            @endforeach

            <tfoot>
                @if(isset($coupon) && !empty($coupon))
                <tr>
                    <td></td>
                    <td class="text-right"><strong>Rabais appliqué</strong></td>
                    <td class="text-right">
                        @if($coupon['type'] == 'shipping')
                            <span class="text-muted">Frais de port offerts</span>
                        @else
                            <span class="text-muted">{{ $coupon['title'] }}</span> &nbsp;{{ $coupon['value'] }}%
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td></td>
                    <td class="text-right"><strong>Frais d'envoi</strong></td>
                    <td class="text-right">{{ number_format((float)$shipping, 2, '.', '') }}  CHF</td>
                </tr>
                <tr class="active">
                    <td></td>
                    <td class="text-right"><strong>Total des produits</strong></td>
                    <td class="text-right">{{ number_format((float)$total, 2, '.', '') }} CHF</td>
                </tr>
            </tfoot>
        </table>

        <div class="toggle-radio pull-right">
            <input type="radio" name="ab" id="a" /><label for="a">
                Par carte de crédit<br/>
                <img src="{{ asset('images/creditcards.png') }}" alt="cartes de crédit">
            </label>
            <input type="radio" name="ab" id="b" checked /><label for="b">
                Sur facture<br/>
                <small>Vous recevrez une facture accompagnant votre livraison</small>
            </label>
        </div>

        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="{{env('STRIPE_API_PUBLIC')}}"
            data-name="www.publications-droit.ch"
            data-currency="CHF"
            data-label="{{ $total}} CHF"
            data-description="Votre commande"
            data-amount="{{ $total * 100 }}">
        </script>


    </div>
</div>
@endif

<ul class="pager">
    <li class="previous"><a href="{{ url('checkout/resume') }}"><span aria-hidden="true">&larr;</span> Retour</a></li>
    <li class="next next-commander"><a class="doAction" data-what="envoyer" data-action="la commande" href="{{ url('checkout/send') }}">Envoyer la commande &nbsp;<span class="glyphicon glyphicon-ok"></span></a></li>
</ul>

@stop