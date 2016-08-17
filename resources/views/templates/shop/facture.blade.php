@extends('templates.layouts.master')
@section('content')

    <div id="content">
        <table id="content-table">
            <tr>
                <td colspan="2"><img height="70mm" src="{{ public_path('files/main/'.\Registry::get('shop.infos.logo')) }}" alt="Unine logo" /></td>
            </tr>
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    <div id="facdroit">
                        <li>{{ \Registry::get('shop.infos.nom') }}</li>
                        <li>{!! \Registry::get('shop.infos.adresse') !!}</li>
                    </div>
                </td>
                <td align="top" width="40%" valign="top">
                    @if($adresse)
                        <ul id="user">
                            {!! (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '') !!}
                            <li>{{ $adresse->civilite_title.' '.$adresse->name }}</li>
                            <li>{{ $adresse->adresse }}</li>
                            {!! (!empty($adresse->complement) ? '<li>'.$adresse->complement.'</li>' : '') !!}
                            {!! (!empty($adresse->cp) ? '<li>'.$adresse->cp_trim.'</li>' : '') !!}
                            <li>{{ $adresse->npa }} {{ $adresse->ville }}</li>
                        </ul>
                    @endif
                </td>
            </tr>
            <tr><td colspan="2" height="1">&nbsp;</td></tr>
        </table>

        <h1 class="title blue">Facture</h1>

        <table class="content-table">
            <tr>
                <td width="59%" align="top" valign="top"  class="misc-infos">
                    @if(!empty($tva))
                        <ul id="tva">
                            <li><strong>{{ \Registry::get('shop.infos.tva') }} TVA</strong></li>
                            @foreach($tva as $line)
                                <li>{{ $line }}</li>
                            @endforeach
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
                            <td width="72%">{{ $order->payement->title }}</td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">Total:</strong></td>
                            <td width="72%">{{ $order->price_cents }} CHF</td>
                        </tr>
                        <tr>
                            <td width="20%"><strong class="blue">Date:</strong></td>
                            <td width="80%">{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">N° facture:</strong></td>
                            <td width="72%">{{ $order->order_no }}</td>
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

                @if(!empty($products))
                    @foreach($products as $product_id => $product)

                        <?php $price_unit = $product->reject(function ($item) { return $item->pivot->isFree; }); ?>
                        <?php $qty = $product->count();?>

                        <tr>
                            <td class="text-left" valign="top">{{ $qty }}</td>
                            <td class="text-left" valign="top">
                                {{ $product->first()->title }} {!! ($product->first()->isbn ? '<br/><small style="font-size:9px;">(ISBN: '.$product->first()->isbn.')</small>' : '') !!}
                            </td>
                            <td class="text-right" valign="top">{!! !$price_unit->isEmpty() ? $price_unit->first()->price_normal.' <span>CHF</span>' : 'gratuit' !!}</td>
                            <td class="text-right" valign="top">{!! !$price_unit->isEmpty() && $price_unit->first()->price_special ? $price_unit->first()->price_special.' CHF' : '' !!}</td>
                            <!-- Calculate price with quantitiy -->
                            <?php $subtotal = (!$price_unit->isEmpty() ? $price_unit->first()->price_cents  : 'gratuit') * $qty; ?>
                            <td class="text-right" valign="top">{{ number_format((float)$subtotal, 2, '.', '') }} <span>CHF</span></td>
                       </tr>
                   @endforeach
                @endif

            </tbody>
        </table>

        <table id="content-table">
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr>
                <!-- Messages for customer -->
                <td width="62%" align="top" valign="top">

                    <h3>Communications</h3>
                    <div class="communications">

                        @if($order->payed_at)
                           <p class="message special">Acquitté le {{ $order->payed_at->format('d/m/Y') }}</p>
                        @endif

                        @if(!empty($messages))
                            @foreach($msgTypes as $msgType)
                                @if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                                    <p class="message '.$msgType.'">{{ $messages[$msgType] }}</p>
                                @endif
                            @endforeach
                            <br/>
                        @endif

                        <p class="message">{{ $messages['remerciements'] }}</p><br/>
                        <p class="message">Neuchâtel, le <?php echo $date; ?></p>
                    </div>

                </td>
                <td width="5%" align="top" valign="top"></td>
                <!-- Total calculations -->
                <td width="33%" align="top" valign="top" class="text-right">
                    <table width="100%" id="content-table" class="total_line" align="right" valign="top">

                        @if($order->coupon_id > 0)
                            <tr align="top" valign="top">
                            @if( $order->coupon->type == 'shipping')
                                <td width="40%" align="top" valign="top" class="text-right">Frais de port offerts</td>
                                <td width="60%" align="top" valign="top" class="text-right"></td>
                            @else
                                <td width="40%" align="top" valign="top" class="text-right text-muted">Rabais {{ $order->coupon->title }}</td>
                                <td width="60%" align="top" valign="top" class="text-right"> -{{ $order->coupon->value }}%</td>
                            @endif
                            </tr>
                        @endif

                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right"><strong>Sous-total:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right">{{ $order->price_cents }} CHF</td>
                        </tr>
                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right"><strong>Frais de port:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right">{{ $order->shipping->price_cents }} CHF</td>
                        </tr>
                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right line"><strong>Total:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right line"><strong>{{ $order->total_with_shipping }} CHF</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- BV id payment type = 1 -->
    @if($order->payement_id == 1)

        {{ ($products->count() > 7 ? '<p style="page-break-after: always;"></p>' : '') }}
        <?php list($francs,$centimes) = $order->price_total_explode; ?>

        <table id="bv-table">
            <tr align="top" valign="top">
                <td width="60mm" align="top" valign="top">
                    <table id="recu" valign="top">
                        <tr>
                            <td align="top" valign="center" height="43mm">
                                @if(!empty($versement))
                                    <ul class="versement">
                                        @foreach($versement as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr><td align="top" valign="center" height="7.6mm" class="compte">{{ $compte }}</td></tr>
                        <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
                    </table>
                </td>
                <td width="62mm" align="top" valign="top">
                    <table id="compte" valign="top">
                        <tr>
                            <td align="top" valign="center" height="43mm">
                                @if(!empty($versement))
                                    <ul class="versement">
                                        @foreach($versement as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr><td align="top" valign="top" height="7.6mm" class="compte"><?php echo $compte; ?></td></tr>
                        <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
                    </table>
                </td>
                <td width="88mm" align="top" valign="top">
                    <table id="versement" valign="top">
                        <tr>
                            <td align="top" valign="top" width="64%" height="20mm">
                                <ul class="versement">
                                    <li>{{ $motif['centre'] }}</li>
                                    <li>{{ $motif['texte'] }}</li>
                                    <li>Facture N° {{ $order->order_no }}</li>
                                </ul>
                            </td>
                            <td align="top" valign="top" width="32%" height="20mm"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif

@stop