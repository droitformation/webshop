@extends('frontend.pubdroit.layouts.master')
@section('content')

<section class="row">
    <div class="col-md-12">

        <p><a href="{{ url('checkout/billing') }}"><span aria-hidden="true">&larr;</span> Retour à l'adresse</a></p>

        <div class="heading-bar">
            <h2>3. Résumé de votre commande</h2>
            <span class="h-line"></span>
        </div>

        <h4>Adresse de livraison</h4>

        <address id="userAdresse">
            @include('frontend.pubdroit.partials.user-livraison')
        </address>

        @if(!Cart::content()->isEmpty())

            <?php $cart  = Cart::content(); ?>
            <div class="accordion-inner no-p" style="border-top: none;">
                <table width="100%" border="0" cellpadding="14" class="cart-resume">
                    <tr class="heading-bar-table">
                        <th colspan="2" width="48%">Ouvrage</th>
                        <th style="text-align: center;" width="23%">Prix par unité</th>
                        <th style="text-align: center;" width="15%">Quantité</th>
                        <th style="text-align: right;"  width="16%">Sous-total</th>
                    </tr>
                    @foreach($cart as $item)
                        <tr>
                            <td valign="top" class="mobile-hidden" align="center">
                                <img style="max-height:80px;" src="{{ asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                            </td>
                            <td width="48%" align="left"><a href="{{ url('product/'.$item->id) }}">{{ $item->name }}</a></td>
                            <td align="center" width="23%">{{ $item->price }} CHF</td>
                            <td align="center" width="15%">{{ $item->qty }}</td>
                            <td align="right" width="16%">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                        </tr>
                    @endforeach
                    @if(isset($coupon) && !empty($coupon))
                        <tr>
                            <td colspan="2"></td>
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
                        <td colspan="4" align="right">
                            <p>Sous-total</p>
                            {!!  isset($coupon) && !empty($coupon) ? '<p><strong>Rabais appliqué</strong></p>' : '' !!}
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
                        <td colspan="5" align="right">

                            <div class="toggle-radio">
                                @foreach($payments as $payment)
                                    <input type="radio" class="paymentType" autocomplete=off name="payement_id" {{ ($payment->id == 1  ? 'checked': '') }} id="{{ $payment->slug }}" value="{{ $payment->id }}" />
                                    <label for="{{ $payment->slug }}">
                                        {{ $payment->title }}<br/>
                                        <img src="{{ asset('images/'.$payment->image.'') }}" alt="{{ $payment->title }}">
                                        <small>{{ $payment->description }}</small>
                                    </label>
                                @endforeach
                            </div>

                            <p class="text-right terms">
                                <label>
                                    <input id="termsAndConditions" type="checkbox"> &nbsp;J'ai lu <a href="#">les termes et conditions générales</a>
                                </label>
                            </p>

                            <hr/>
                            <a id="btn-invoice" class="doAction more-btn btn-success btn-commande"
                               data-checked="true"
                               data-what="envoyer"
                               data-action="la commande" href="{{ url('checkout/send') }}">Envoyer la commande &nbsp;<i class="fa fa-paper-plane pull-right"></i>
                            </a>
                            <a id="btn-stripe" style="display: none;" class="more-btn btn-success btn-commande"
                               data-toggle="modal"
                               data-target="#payment-stripe">Payer la commande  &nbsp;<i class="fa fa-paper-plane pull-right"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
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
        @endif

    </div>
</section>

@stop