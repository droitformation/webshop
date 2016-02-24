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
                <td colspan="2"><img height="80mm" src="<?php echo public_path('files/logos/facdroit.jpg'); ?>" alt="Unine logo" /></td>
            </tr>
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    <?php
                        if(!empty($expediteur)){
                            echo '<ul id="facdroit">';
                            foreach($expediteur as $line){
                                echo '<li>'.$line.'</li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                    <p class="tva"><?php echo $tva['numero']; ?></p>
                </td>
                <td align="top" width="40%" valign="top">
                    <?php
                        $adresse = $groupe->adresse_facturation;

                        if($adresse)
                        {
                            echo '<ul id="user">';
                            echo (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '');
                            echo '<li>'.$adresse->civilite_title.' '.$groupe->adresse_facturation->name.'</li>';
                            echo '<li>'.$adresse->adresse.'</li>';
                            echo (!empty($adresse->complement) ? '<li>'.$adresse->complement.'</li>' : '');
                            echo (!empty($adresse->cp) ? '<li>'.$adresse->cp_trim.'</li>' : '');
                            echo '<li>'.$adresse->npa.' '.$adresse->ville.'</li>';
                            echo '</ul>';
                        }
                    ?>
                </td>
            </tr>
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
        </table>
    </div>

    <div class="content">

        <h1 class="title blue">
            <?php
                if($rappel)
                {
                    echo '<span class="red">'.$rappel.''.($rappel > 1 ? 'ème' : 'ère').' Rappel</span> ';
                }
            ?>
            FACTURE
        </h1>

        <?php
            $numeros      = '';
            $participants = '';
            $somme        = 0;
            if(!$inscriptions->isEmpty())
            {
                foreach($inscriptions as $inscription)
                {
                    $numeros      .= '<p>'.$inscription->inscription_no.'</p>';
                    $participants .= '<p>'.$inscription->participant->name.'</p>';
                    $somme        += $inscription->price_cents;
                }
            }
        ?>

        <table class="content-table" valign="top">
            <tr><td height="5">&nbsp;</td></tr>
            <tr valign="top">
                <td width="40%" valign="top">
                    <strong>Participants:</strong> <?php echo $participants; ?>
                </td>
                <td width="60%" valign="top">
                    <strong>N° d'inscriptions:</strong> <?php echo $numeros; ?>
                </td>
            </tr>
            <tr><td height="5">&nbsp;</td></tr>
        </table>

        <table class="content-table content-wide" valign="top">
            <tr valign="top">
                <td valign="top">
                    <h2><?php echo $inscriptions->first()->colloque->titre; ?></h2>
                    <h3><?php echo $inscriptions->first()->colloque->soustitre; ?></h3>
                </td>
            </tr>
            <tr><td height="5">&nbsp;</td></tr>
            <tr>
                <td valign="top">
                    <h3 class="titre-info"><strong>Date:</strong> <?php echo $inscriptions->first()->colloque->event_date; ?></h3>
                    <h3 class="titre-info"><strong>Lieu:</strong> <?php echo $inscriptions->first()->colloque->location->name.', '.$inscriptions->first()->colloque->location->adresse; ?></h3>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <table class="content-table" valign="top">
            <tr><td height="5">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top">

                    <table class="content-table facture-table" valign="top">
                        <tr valign="top">
                            <td width="40%" valign="top">
                                <p>Nous vous confirmons les participations au:</p>
                            </td>
                            <td width="60%" valign="top">
                                <p><strong><?php echo $inscriptions->first()->colloque->titre; ?></strong></p>
                                <p><?php echo $inscriptions->first()->colloque->event_date; ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td width="40%" valign="top">
                                <p>Le montant des inscriptions est de:</p>
                            </td>
                            <td width="60%" valign="top">
                                <p><strong><?php echo $somme; ?> CHF</strong></p>
                                <small>(montant non-soumis à la TVA)</small>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr><td height="20">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top">
                    <p class="message"><?php echo $messages['remerciements']; ?></p>
                    <p class="message">Neuchâtel, le <?php echo $date; ?></p>
                </td>
            </tr>
            <tr><td height="10">&nbsp;</td></tr>
            <tr>
                <td align="right" valign="top">
                    <p class="message"><strong><?php echo $signature; ?></strong></p>
                </td>
            </tr>
            <tr><td height="20">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top">
                    <?php
                        if(!empty($annexes))
                        {
                            echo '<p class="red"><strong>Annexe'.(count($annexes) > 1 ? 's' : '').': '.implode(',',$annexes).'</strong></p>';
                        }
                    ?>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>