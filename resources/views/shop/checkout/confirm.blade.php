@extends('frontend.pubdroit.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Confirmation de la commande</h2>
    </div>
</div>

<!-- Cart  -->
@include('shop.partials.cart')

@if(!Cart::content()->isEmpty())
<div class="row" id="panier" style="margin-bottom: 5px;">
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

        <p class="text-right"><label><input id="termsAndConditions" type="checkbox"> &nbsp;J'ai lu <a href="#">les termes et conditions générales</a></label></p>

        <div class="toggle-radio pull-right">
            @foreach($payments as $payment)
            <input type="radio" class="paymentType" autocomplete=off name="payement_id" {{ ($payment->id == 1  ? 'checked': '') }} id="{{ $payment->slug }}" value="{{ $payment->id }}" />
                <label for="{{ $payment->slug }}">
                    {{ $payment->title }}<br/>
                  <img src="{{ asset('images/'.$payment->image.'') }}" alt="{{ $payment->title }}">
                    <small>{{ $payment->description }}</small>
              </label>
            @endforeach
        </div>

    </div>
</div>
@endif


<!-- Modal -->
<div class="modal fade " id="payment-stripe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Payer</h4>
            </div>
            <div class="modal-body">
                @include('shop.gateways.stripe')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

<ul class="pager">
    <li class="previous"><a href="{{ url('checkout/resume') }}"><span aria-hidden="true">&larr;</span> Retour</a></li>
    <li class="next next-commander">
        <a id="btn-invoice" class="doAction btn-commande" data-checked="true" data-what="envoyer" data-action="la commande" href="{{ url('checkout/send') }}">
            Envoyer la commande &nbsp;<span class="glyphicon glyphicon-ok"></span>
        </a>

        <a id="btn-stripe" style="display: none;" class="btn-commande" data-toggle="modal" data-target="#payment-stripe">
            Payer la commande &nbsp;<span class="glyphicon glyphicon-ok"></span>
        </a>
    </li>
</ul>

@stop