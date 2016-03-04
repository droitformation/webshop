<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/common.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/bon.css');?>" media="screen" />
</head>
<body>

    <div class="content">
        <table class="content-table">
            <tr>
                <td colspan="2">
                    <img height="70mm" src="{{ public_path('files/main/'.\Registry::get('inscription.infos.logo')) }}" alt="Unine logo" />
                </td>
            </tr>
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    <ul id="facdroit">
                        <li>{!! \Registry::get('inscription.infos.nom') !!}</li>
                        <li>{!! \Registry::get('inscription.infos.adresse') !!}</li>
                    </ul>
                    <p class="tva">{{ $tva['numero'] }}</p>
                </td>
                <td align="top" width="40%" valign="top">
                    @include('templates.partials.adresse',['adresse' => $generate->getAdresse()])
                </td>
            </tr>
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
        </table>
    </div>

    <div class="content">

        <?php $rappel = (isset($nbr) ? '<span class="red">'.$nbr.''.($nbr > 1 ? 'ème' : 'ère').' Rappel</span>' : ''); ?>
        <h1 class="title blue">
            {{ $rappel }}FACTURE {{ !is_array($generate->getNo()) ? $generate->getNo() : '' }}
        </h1>

        @if(is_array($generate->getNo()))
            <table class="content-table" valign="top">
                <tr><td height="5">&nbsp;</td></tr>
                <tr valign="top">
                    <td width="40%" valign="top"><strong>Participants:</strong></td>
                    <td width="60%" valign="top"><strong>N° d'inscriptions:</strong></td>
                </tr>
                @foreach($generate->getNo() as $no => $participant)
                    <tr valign="top">
                        <td width="40%" valign="top">{{ $participant }}</td>
                        <td width="60%" valign="top">{{ $no }}</td>
                    </tr>
                @endforeach
                <tr><td height="5">&nbsp;</td></tr>
            </table>
        @endif

        <table class="content-table content-wide" valign="top">
            @include('templates.partials.colloque',['colloque' => $generate->getColloque()])
        </table>

    </div>

    <div class="content">
        <table class="content-table" valign="top">
            <tr valign="top">
                <td valign="top">
                    <table class="content-table facture-table" valign="top">
                        <tr valign="top">
                            <td width="40%" valign="top">
                                {!! !is_array($generate->getNo()) ? ' <p>Le montant de l\'inscription est de:</p>' : ' <p>Le montant des inscriptions est de:</p>' !!}
                            </td>
                            <td width="60%" valign="top">
                                <p><strong>{{ $generate->getPrice() }} CHF</strong></p>
                                <small>(montant non-soumis à la TVA)</small>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr><td height="20">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top">
                    <p class="message">{{ $messages['remerciements'] }}</p>
                    <p class="message">Neuchâtel, le <?php echo $date; ?></p>
                </td>
            </tr>
            <tr><td height="10">&nbsp;</td></tr>
            <tr>
                <td align="right" valign="top"><p class="message"><strong>{{ $signature }}</strong></p></td>
            </tr>
            <tr><td height="20">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top" style="margin-top: 20px;">
                    @if(!empty($generate->getColloque()->annexe))
                        <p class="red"><strong>Annexe{{ (count($generate->getColloque()->annexe) > 1 ? 's' : '') }}: {{ implode(',',$generate->getColloque()->annexe) }}</strong></p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>