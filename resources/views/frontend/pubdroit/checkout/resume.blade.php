@extends('frontend.pubdroit.layouts.master')
@section('content')

<section class="row">
    <div class="col-md-12">

        <p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit/checkout/billing') }}"><span aria-hidden="true">&larr;</span> Retour à l'adresse</a></p>

        <div class="heading-bar">
            <h2>3. Résumé de votre commande</h2>
            <span class="h-line"></span>
        </div>

        <h4>Adresse de livraison</h4>

        <address id="userAdresse">
            @include('frontend.pubdroit.partials.user-livraison')
        </address>

        @if(!Cart::instance('shop')->content()->isEmpty() || !Cart::instance('abonnement')->content()->isEmpty())

            <?php $shop_cart = Cart::instance('shop')->content(); ?>
            <?php $abo_cart  = Cart::instance('abonnement')->content(); ?>

            <div class="accordion-inner no-p" style="border-top: none;">
                <table width="100%" border="0" cellpadding="14" class="cart-resume">
                    <tr class="heading-bar-table">
                        <th colspan="2" width="55%">Ouvrage/abonnement</th>
                        <th style="text-align: center;" width="15%">Prix par unité</th>
                        <th style="text-align: center;" width="15%">Quantité</th>
                        <th style="text-align: right;"  width="15%">Sous-total</th>
                    </tr>

                    @foreach($shop_cart as $item)
                        <tr>
                            <td valign="middle" width="15%">
                                <img style="max-height:80px;" src="{{ secure_asset('files/products/'.$item->options->image ) }}" alt="{{ $item->name }}">
                            </td>
                            <td width="40%" align="left"><a href="{{ url('pubdroit/product/'.$item->id) }}">{{ $item->name }}</a></td>
                            <td align="center" width="15%">{{ $item->model->price_cents }} CHF</td>
                            <td align="center" width="15%">{{ $item->qty }}</td>
                            <td align="right" width="15%">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                        </tr>
                    @endforeach

                    @foreach($abo_cart as $item)
                        <tr class="order_abo">
                            <td valign="middle" width="15%">
                                <img src="{{ secure_asset('files/main/'.$item->options->image) }}" />
                            </td>
                            <td valign="middle" width="40%" class="text-left">
                                <p>Demande d'abonnement <strong>{{ $item->name }}</strong></p>
                                {!! $item->model->remarque !!}
                            </td>
                            <td align="center" width="15%">{{ $item->price }} CHF</td>
                            <td align="center" width="15%">{{ $item->qty }}</td>
                            <td align="right" width="15%">{{ number_format((float)($item->price * $item->qty), 2, '.', '') }} CHF</td>
                        </tr>
                    @endforeach

                    @include('frontend.pubdroit.checkout.calcul.coupon')
                    @include('frontend.pubdroit.checkout.calcul.subtotal')

                    <tr>
                        <td colspan="5" align="right">

                            @if(isset($payments))
                                @foreach($payments as $payment)
                                    <input type="hidden" name="payement_id" checked value="{{ $payment->id }}" />
                                    <p><strong>Payement {{ $payment->title }}</strong><br/>{{ $payment->description }}</p><br/>
                                @endforeach
                            @endif

                            <p class="text-right terms">
                                <label><input id="termsAndConditions" name="termsAndConditions" type="checkbox"> &nbsp;J'ai lu <a href="#">les termes et conditions générales</a></label>
                            </p>

                            <hr/>
                            <a id="btn-invoice" class="doAction more-btn btn-success btn-commande"
                               data-checked="true"
                               data-what="envoyer"
                               data-action="la commande" href="{{ url('pubdroit/checkout/send') }}">Envoyer la commande &nbsp;<i class="fa fa-paper-plane pull-right"></i>
                            </a>

                        </td>
                    </tr>
                </table>
            </div>

        @endif

    </div>
</section>

@stop