<?php $color = $campagne->newsletter->second_color ? $campagne->newsletter->second_color : $campagne->newsletter->color; ?>
<tr>
    <td align="center">

        <table bgcolor="{{ $color }}" width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="resetTable headerInfos">
            <tr bgcolor="{{ $color }}"><td colspan="4" height="10"></td></tr><!-- space -->

            <tr bgcolor="{{ $color }}">
	            <?php $width_tbl = $campagne->newsletter->logo_soutien ? 430 : 600; ?>
                <td width="{{ $width_tbl }}">
                    <table width="{{ $width_tbl }}" bgcolor="{{ $color }}" border="0" cellpadding="0" cellspacing="0" align="center" class="resetTable" style="height: 60px;">
                        @if(!empty($campagne->sujet ))
                        <tr bgcolor="{{ $color }}">
                            <td width="20"></td>
                            <td colspan="2" align="left">
                                <h1 class="header"><span style="color: #fff;font-size: 18px;display: block;">{!! $campagne->sujet !!}&nbsp;</span></h1>
                            </td>
                            <td width="20"></td>
                        </tr>
                        @endif
                        @if(!empty($campagne->auteurs ))
                            <tr bgcolor="{{ $color }}">
                                <td width="20"></td>
                                <td align="left">
                                    <h2 class="header headerSmall">
                                        <span style="color: #fff;font-size: 15px;display: block;">{{ $campagne->auteurs }}&nbsp;</span>
                                    </h2>
                                </td>
                                <td width="20"></td>
                            </tr>
                        @endif
                    </table>
                </td>

                @if($campagne->newsletter->logo_soutien)
	                <td width="110" style="text-align: left; ">
                        <small style="text-align: left; font-family: sans-serif;color: #fff;font-size: 11px;">Avec le soutien de</small>
                        <a target="_blank" href="#">
                            <img style="max-width: 105px;" alt="soutien" src="{{ secure_asset($campagne->newsletter->logo_soutien) }}" />
                        </a>
	                </td>
                @endif

            </tr>

            <tr bgcolor="{{ $color }}"><td colspan="4" height="10"></td></tr>
        </table>

    </td>
</tr>