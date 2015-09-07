<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; page-break-inside: auto;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/common.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/generate/invoice.css');?>" media="screen" />
</head>
<body style="position: relative;height:297mm;">

<?php

    if(!$inscriptions->isEmpty())
    {
        foreach($inscriptions as $inscription)
        {
            $numeros[] = 'N° '.$inscription->inscription_no;
        }

        $numeros = implode(',',$numeros);
        $sum     = $inscriptions->sum('price_cents');
        $sum     = number_format($sum, 2);
    }

    list($francs,$centimes) = explode('.',$sum);

?>

    <table id="bv-table">
        <tr align="top" valign="top">
            <td width="60mm" align="top" valign="top">
                <table id="recu" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <?php
                                echo '<ul class="versement">';
                                echo '<li>'.$groupe->colloque->compte->adresse.'</li>';
                                echo '</ul>';
                            ?>
                        </td>
                    </tr>
                    <tr><td align="top" valign="center" height="7.6mm" class="compte"><?php echo $groupe->colloque->compte->compte; ?></td></tr>
                    <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
                </table>
            </td>
            <td width="62mm" align="top" valign="top">
                <table id="compte" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <?php
                                echo '<ul class="versement">';
                                    echo '<li>'.$groupe->colloque->compte->adresse.'</li>';
                                echo '</ul>';
                            ?>
                        </td>
                    </tr>
                    <tr><td align="top" valign="top" height="7.6mm" class="compte"><?php echo $groupe->colloque->compte->compte; ?></td></tr>
                    <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs"><?php echo $francs; ?></span><?php echo $centimes; ?></td></tr>
                </table>
            </td>
            <td width="88mm" align="top" valign="top">
                <table id="versement" valign="top">
                    <tr>
                        <td align="top" valign="top" width="64%" height="20mm">
                            <?php
                                echo '<ul class="versement versement-bv">';
                                    echo '<li>'.$groupe->colloque->compte->motif.'</li>';
                                    echo '<li class="inscription_no">'.$numeros.'</li>';
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