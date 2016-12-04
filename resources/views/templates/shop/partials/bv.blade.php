<!-- BV id payment type = 1 -->
@if($order->payement_id == 1)

    {{ ($products->count() > 7 ? '<p style="page-break-after: always;"></p>' : '') }}
    <?php list($francs,$centimes) = explode('.', $montant) or $order->price_total_explode; ?>

    <table id="bv-table">
        <tr align="top" valign="top">
            <td width="60mm" align="top" valign="top">
                <table id="recu" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            @if(!empty($versement))
                                <ul class="versement">
                                    {!! "<li>".implode('</li><li>', $versement)."</li>" !!}
                                </ul>
                            @endif
                        </td>
                    </tr>
                    <tr><td align="top" valign="center" height="7.6mm" class="compte">{{ \Registry::get('shop.compte.livre') }}</td></tr>
                    <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
                </table>
            </td>
            <td width="62mm" align="top" valign="top">
                <table id="compte" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            @if(!empty($versement))
                                <ul class="versement">
                                    {!! "<li>".implode('</li><li>', $versement)."</li>" !!}
                                </ul>
                            @endif
                        </td>
                    </tr>
                    <tr><td align="top" valign="top" height="7.6mm" class="compte">{{ \Registry::get('shop.compte.livre') }}</td></tr>
                    <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
                </table>
            </td>
            <td width="88mm" align="top" valign="top">
                <table id="versement" valign="top">
                    <tr>
                        <td align="top" valign="top" width="64%" height="20mm">
                            <ul class="versement">
                                <li>{!! $motif['centre'] !!}</li>
                                <li>{!! $motif['texte'] !!}</li>
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