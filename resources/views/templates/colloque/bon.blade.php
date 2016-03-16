<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo public_path('css/generate/common.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo public_path('css/generate/bon.css');?>" media="screen" />
</head>
<body>

    <div class="content">
        <table class="content-table">
            <tr>
                <td><img height="80mm" src="<?php echo public_path('files/logos/facdroit.png'); ?>" alt="Unine logo" /></td>
                <td align="right">
                    <div class="visible-print">
                        @if(isset($code))
                            <img src="data:image/png;base64, {{ $code }}">
                        @endif
                    </div>
                </td>
            </tr>
            <tr><td colspan="2" height="10">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    @if(!empty($expediteur))
                        <ul id="facdroit">
                            @foreach($expediteur as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td align="top" width="40%" valign="top">

                    @include('templates.partials.adresse',['adresse' => $generate->getAdresse()])

                    @if($generate->getParticipant())
                        <p style="margin-top: 25px;"><strong>Participant: </strong>{{ $generate->getParticipant() }}</p>
                    @endif

                </td>
            </tr>
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
        </table>
    </div>

    <div class="content">
        <h1 class="title blue">BON DE PARTICIPATION <?php echo $generate->getNo(); ?></h1>
        <p class="red"><small>A présenter lors de votre arrivée</small></p>

        <table class="content-table content-wide" valign="top">

            @include('templates.partials.colloque',['colloque' => $generate->getColloque()])

            @include('templates.partials.occurrences',['occurrences' => $generate->getOccurrences()])

            <tr><td height="5">&nbsp;</td></tr>

            <?php $options = $generate->getOptions(); ?>

            @if(!empty($options))
                <tr>
                    <td valign="top">
                        <h4>Choix:</h4>
                        <ul class="options">
                            @foreach($options as $option)
                                <li>{{ $option['title'] }}</li>
                                {!! isset($option['choice']) ? '<li class="blue text-indent">'.$option['choice'].'</li>' : '' !!}
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endif
        </table>
    </div>


   <?php if($generate->getColloque()->location->location_map && !$generate->getOccurrences()){ ?>
    <div class="content">
        <table class="content-table" valign="top">
            <tr><td height="15">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top" align="center">
                    <img style="max-width: 110mm" src="<?php echo public_path($generate->getColloque()->location->location_map); ?>" alt="Carte" />
                </td>
            </tr>
        </table>
    </div>
    <?php } ?>

</body>
</html>