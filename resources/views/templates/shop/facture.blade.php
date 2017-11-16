@extends('templates.layouts.master')
@section('content')

    <div id="content">

        @include('templates.shop.partials.header')

        <h1 class="title blue">
            {!! isset($rappel) ? '<span class="red">'.$generate->order()->rappels->count().''.($generate->order()->rappels->count() > 1 ? 'ème' : 'er').' Rappel</span> ife' : 'Facture' !!}
        </h1>

        <table class="content-table">
            <tr>
                <td width="59%" align="top" valign="top"  class="misc-infos">
                    @if(!empty($tva))
                        <ul id="tva">
                            <li><strong>{{ \Registry::get('shop.infos.tva') }}</strong></li>
                            {!! "<li>".implode('</li><li>', $tva)."</li>" !!}
                        </ul>
                    @endif
                    <div class="coordonnees">
                        <h4>Coordonnées pour le paiement</h4>
                        <p>IBAN: {{ \Registry::get('inscription.infos.iban') }}</p>
                    </div>
                </td>
                <td width="1%" align="top" valign="top"></td>
                <td width="40%" align="top" valign="middle" class="misc-infos">
                    <table id="content-table" class="infos">
                        <tr>
                            <td width="28%"><strong class="blue">Payement</strong></td>
                            <td width="72%">{{ $generate->order()->payement->title }}</td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">Total:</strong></td>
                            <td width="72%">{{ $generate->order()->total_with_shipping}} CHF</td>
                        </tr>
                        <tr>
                            <td width="20%"><strong class="blue">Date:</strong></td>
                            <td width="80%">{{ $generate->order()->created_at->formatLocalized('%d %B %Y') }}</td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">N° facture:</strong></td>
                            <td width="72%">{{ $generate->order()->order_no }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table id="invoice-table">
            <thead>
                <tr>
                    <th width="7%" class="text-left">Qt</th>
                    <th width="45%" class="text-left">Nom de l'ouvrage</th>
                    <th width="16%" class="text-right">Prix à l'unité</th>
                    <th width="16%" class="text-right">Prix spécial</th>
                    <th width="16%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>

                @if(!$generate->getProducts()->isEmpty())
                    @foreach($generate->getProducts() as $product_id => $product)

                        <?php $price_unit = $product->reject(function ($item) { return $item->pivot->isFree; }); ?>
                        <?php $qty = $product->count();?>

                        <tr>
                            <td class="text-left" valign="top">{{ $qty }}</td>
                            <td class="text-left" valign="top">
                                {{ $product->first()->title }} {!! ($product->first()->isbn ? '<br/><small style="font-size:9px;">(ISBN: '.$product->first()->isbn.')</small>' : '') !!}
                            </td>
                            <td class="text-right" valign="top">
                                {!! !$price_unit->isEmpty() ? $price_unit->first()->price_normal.' <span>CHF</span>' : 'gratuit' !!}
                                {!! !$price_unit->isEmpty() ? '<br/><small style="font-size:9px;">Prix public recommandé</small>' : '' !!}
                            </td>
                            <td class="text-right" valign="top">
                                {!! !$price_unit->isEmpty() && $price_unit->first()->price_special ? $price_unit->first()->price_special.' CHF' : '' !!}
                            </td>

                            <!-- Calculate price with quantitiy -->
                            @if(!$price_unit->isEmpty())
                                <?php $subtotal = $price_unit->first()->price_cents * $qty; ?>
                                <td class="text-right" valign="top">{{ number_format((float)$subtotal, 2, '.', '') }} <span>CHF</span></td>
                            @else
                                <td class="text-right" valign="top">gratuit</td>
                            @endif

                       </tr>
                   @endforeach
                @endif

            </tbody>
        </table>

        @include('templates.shop.partials.infos')
    </div>

    @include('templates.shop.partials.bv')

@stop