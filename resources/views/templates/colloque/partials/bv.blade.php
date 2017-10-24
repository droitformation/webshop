<?php list($francs,$centimes) = explode('.',$generate->getPrice()); ?>

<table id="bv-table{{ $print ? '-print' : '' }}" >
    <tr align="top" valign="top">
        <td width="60mm" align="top" valign="top">
            <table id="recu" valign="top">
                <tr>
                    <td align="top" valign="center" height="43mm">
                        <ul class="versement">
                            <li>{!! $generate->getColloque()->compte->adresse !!}</li>
                        </ul>
                    </td>
                </tr>
                <tr><td align="top" valign="center" height="7.6mm" class="compte"><?php echo $generate->getColloque()->compte->compte; ?></td></tr>
                <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
            </table>
        </td>
        <td width="62mm" align="top" valign="top">
            <table id="compte" valign="top">
                <tr>
                    <td align="top" valign="center" height="43mm">
                        <ul class="versement">
                            <li>{!! $generate->getColloque()->compte->adresse !!}</li>
                        </ul>
                    </td>
                </tr>
                <tr><td align="top" valign="top" height="7.6mm" class="compte"><?php echo $generate->getColloque()->compte->compte; ?></td></tr>
                <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
            </table>
        </td>
        <td width="88mm" align="top" valign="top">
            <table id="versement" valign="top">
                <tr>
                    <td align="top" valign="top" width="64%" height="22mm">
                        <ul class="versement versement-bv">
                            <li>{!! $generate->getColloque()->compte->motif !!}</li>
                            <li>
                                @if(!is_array($generate->getNo()))
                                    {{  $generate->getNo() }}
                                @endif

                                @if(is_array($generate->getNo()) && !empty($generate->getNo()))
                                    {{ implode(', ',array_keys($generate->getNo())) }}
                                @endif
                            </li>
                        </ul>
                    </td>
                    <td align="top" valign="top" width="32%" height="22mm"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>