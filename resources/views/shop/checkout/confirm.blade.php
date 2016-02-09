@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p><a href="{{ url('shop') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a></p>

            <div class="heading-bar">
                <h2>Résumé de votre commande</h2>
                <span class="h-line"></span>
            </div>

            <section class="checkout-holder">
                <div class="r-title-bar title-left"><strong>Données</strong></div>
                <article class="checkout-wrapper">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Adresse de livraison</h4>
                            <address>
                                <?php $user->adresse_livraison->load(['pays','civilite']); ?>
                                <strong>{{ $user->adresse_livraison->civilite->title }} {{ $user->adresse_livraison->first_name }} {{ $user->adresse_livraison->last_name }}</strong><br>
                                {!! !empty($user->adresse_livraison->company) ? $user->adresse_livraison->company.'<br>' : '' !!}
                                {{ $user->adresse_livraison->adresse }}<br>
                                {!! !empty($user->adresse_livraison->complement) ? $user->adresse_livraison->complement.'<br>' : '' !!}
                                {!! !empty($user->adresse_livraison->cp) ? $user->adresse_livraison->cp.'<br>' : '' !!}
                                {{ $user->adresse_livraison->npa }} {{ $user->adresse_livraison->ville }}<br>
                                {{ $user->adresse_livraison->pays->title }}
                            </address>
                            <p><a href="{{ url('checkout/billing') }}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> &nbsp;&Eacute;diter l'adresse</a></p>
                        </div>
                        <div class="col-md-6">
                            <h4>Informations</h4>
                            <dl>
                                <dt>Livraison et disponibilité</dt>
                                <dd>
                                    Les expéditions se font du lundi au vendredi inclus (sauf jours fériés) dans la mesure des disponibilités de l’ouvrage.
                                    Un e-mail de confirmation vous est envoyé pour certifier la bonne réception de votre commande.
                                </dd>
                            </dl>
                        </div>
                    </div>
                </article>
            </section>

            <h4>Panier</h4>
            <!-- Start Accordian Section -->
            @include('frontend.pubdroit.partials.cart', ['confirm' => true])

            @if(!Cart::content()->isEmpty())

                <?php $cart  = Cart::content(); ?>
                <div class="accordion-inner no-p">
                    <table width="100%" border="0" cellpadding="14" class="cart-resume">
                        <tr class="heading-bar-table">
                            <th width="58%">Product Name</th>
                            <th style="text-align: center;" width="18%">Price</th>
                            <th style="text-align: center;" width="10%">Quantity</th>
                            <th style="text-align: right;"  width="16%">Subtotal </th>
                        </tr>
                        @foreach($cart as $item)
                            <tr>
                                <td width="458" align="left"><a href="{{ url('product/'.$item->id) }}">{{ $item->name }}</a></td>
                                <td align="center" width="18%">{{ $item->price }} CHF</td>
                                <td align="center" width="10%">{{ $item->qty }}</td>
                                <td align="right" width="16%">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                            </tr>
                        @endforeach
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
                            <td colspan="3" align="right">
                                <p>Subtotal</p>
                                {{ isset($coupon) && !empty($coupon) ? '<p><strong>Rabais appliqué</strong></p>' : '' }}
                                <p>Frais d'envoi</p>
                                <p><strong>Total</strong></p>
                            </td>
                            <td align="right">
                                <p>{{ number_format((float)Cart::total(), 2, '.', '') }} CHF</p>
                                @if(isset($coupon) && !empty($coupon))
                                    @if($coupon['type'] == 'shipping')
                                        <p><span class="text-muted">Frais de port offerts</span></p>
                                    @else
                                        <p><span class="text-muted">{{ $coupon['title'] }}</span> &nbsp;{{ $coupon['value'] }}%</p>
                                    @endif
                                @endif
                                <p>{{ number_format((float)$shipping, 2, '.', '') }}  CHF</p>
                                <p><strong>{{ number_format((float)$total, 2, '.', '') }} CHF</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="left"></td>
                            <td><a href="#" class="more-btn">Place Order</a> </td>
                        </tr>
                    </table>
                </div>
            @endif

        </div>
    </section>

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