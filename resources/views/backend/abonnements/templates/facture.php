<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/common.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/invoice.css');?>" media="screen" />
</head>
<body style="position: relative;height:297mm;">
    <div id="content">
        <table id="content-table">
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
                </td>
                <td align="top" width="40%" valign="top">
                    <?php
                        if($adresse)
                        {
                            echo '<ul id="user">';
                            echo (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '');
                            echo '<li>'.$adresse->civilite_title.' '.$adresse->name.'</li>';
                            echo '<li>'.$adresse->adresse.'</li>';
                            echo (!empty($adresse->complement) ? '<li>'.$adresse->complement.'</li>' : '');
                            echo (!empty($adresse->cp) ? '<li>'.$adresse->cp_trim.'</li>' : '');
                            echo '<li>'.$adresse->npa.' '.$adresse->ville.'</li>';
                            echo '</ul>';
                        }
                    ?>
                </td>
            </tr>
            <tr><td colspan="2" height="1">&nbsp;</td></tr>
        </table>

        <h1 class="title blue">Facture</h1>

        <table class="content-table">
            <tr>
                <td width="33%" align="middle" class="misc-infos">
                    <h4><strong><?php echo $abo->abo_no; ?></strong></h4>
                </td>
                <td width="1%"  align="top"></td>
                <td width="30%" align="middle" style="text-align: center;" class="misc-infos">
                    <?php if(!empty($tva)) { echo \Registry::get('shop.infos.tva').' TVA'; } ?>
                </td>
                <td width="1%" align="top" valign="top"></td>
                <td width="35%" align="top" valign="middle" class="misc-infos">
                    <table id="content-table" class="infos">
                        <tr>
                            <td width="20%"><strong class="blue">Date:</strong></td>
                            <td width="80%"><?php echo $abo->created_at->formatLocalized('%d %B %Y'); ?></td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">Total:</strong></td>
                            <td width="72%"><?php echo $abo->price_cents; ?> CHF</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan="2" height="1">&nbsp;</td></tr>
        </table>

        <!-- Total calculations -->
        <div id="abo-table">
            <?php $traduction = ['year' => 'annuelle', 'semester' => 'semestrielle', 'month' => 'mensuelle']; ?>
            <h2>Abonnement à la publication <?php echo $traduction[$abo->abo->plan]; ?> <strong><?php echo $abo->abo->title; ?></strong></h2>

            <table class="content-table content-abo">
                <tr><td colspan="3" height="1">&nbsp;</td></tr>
                <tr>
                    <td width="10%"><?php echo $abo->exemplaires; ?></td>
                    <td width="60%">exemplaire<?php echo ($abo->exemplaires > 1) ? 's' : '' ?> du numéro <?php echo $abo->abo->current_product->reference; ?> (<?php echo \Registry::get('shop.infos.taux_reduit'); ?>% TVA incluse)</td>
                    <td width="30%" align="right"><strong><?php echo $abo->price_cents; ?> CHF</strong></td>
                </tr>
                <tr><td colspan="3" height="1">&nbsp;</td></tr>
                <tr>
                    <td width="10%"></td>
                    <td width="80%" align="middle">
                        <small>Conditions de paiement <?php echo \Registry::get('shop.abo.days'); ?> jours net</small>
                    </td>
                    <td width="10%"></td>
                </tr>
            </table>

        </div>

        <table id="content-table">
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr>
                <!-- Messages for customer -->
                <td width="62%" align="top" valign="top">

                    <h3>Communications</h3>
                    <div class="communications"><?php echo \Registry::get('shop.abo.message'); ?></div>
                    <div class="communications">
                        <?php

                        if($abo->payed_at)
                        {
                            echo '<p class="message special">Acquitté le '.$abo->payed_at->format('d/m/Y').'</p>';
                        }

                        if(!empty($messages))
                        {
                            foreach($msgTypes as $msgType)
                            {
                                if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                                {
                                    echo '<p class="message '.$msgType.'">'.$messages[$msgType].'</p>';
                                }
                            }
                            echo '<br/>';
                        }
                        ?>
                        <p class="message"><?php echo $messages['remerciements']; ?></p><br/>
                        <p class="message">Neuchâtel, le <?php echo $date; ?></p>
                    </div>

                </td>
                <td width="5%" align="top" valign="top"></td>
            </tr>
        </table>
    </div>

    <!-- BV -->
    <?php list($francs,$centimes) = $abo->price_total_explode; ?>

    <table id="bv-table">
        <tr align="top" valign="top">
            <td width="60mm" align="top" valign="top">
                <table id="recu" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <?php
                            if(!empty($versement)){
                                echo '<ul class="versement">';
                                foreach($versement as $line){
                                    echo '<li>'.$line.'</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr><td align="top" valign="center" height="7.6mm" class="compte"><?php echo $compte; ?></td></tr>
                    <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
                </table>
            </td>
            <td width="62mm" align="top" valign="top">
                <table id="compte" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <?php
                            if(!empty($versement)){
                                echo '<ul class="versement">';
                                foreach($versement as $line)
                                {
                                    echo '<li>'.$line.'</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr><td align="top" valign="top" height="7.6mm" class="compte"><?php echo $compte; ?></td></tr>
                    <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
                </table>
            </td>
            <td width="88mm" align="top" valign="top">
                <table id="versement" valign="top">
                    <tr>
                        <td align="top" valign="top" width="64%" height="20mm">
                            <?php
                                echo '<ul class="versement">';
                                echo '<li>'.$abo->abo->title.'</li>';
                                echo '<li>'.$abo->abo_no.'</li>';
                                echo '</ul>';
                            ?>
                        </td>
                        <td align="top" valign="top" width="32%" height="20mm"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>