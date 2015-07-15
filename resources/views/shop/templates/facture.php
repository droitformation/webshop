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

                        $adresse = $order->user->adresse_facturation;

                        if($adresse)
                        {
                            echo '<ul id="user">';
                            echo (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '');
                            echo '<li>'.$adresse->civilite_title.' '.$order->user->name.'</li>';
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
                <td width="59%" align="top" valign="top"  class="misc-infos">
                    <?php
                    if(!empty($tva)){
                        echo '<ul id="tva">';
                        foreach($tva as $line){
                            echo '<li>'.$line.'</li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </td>
                <td width="1%" align="top" valign="top"></td>
                <td width="40%" align="top" valign="middle" class="misc-infos">
                    <table id="content-table" class="infos">
                        <tr>
                            <td width="28%"><strong class="blue">Total:</strong></td>
                            <td width="72%"><?php echo $order->price_cents; ?> CHF</td>
                        </tr>
                        <tr>
                            <td width="20%"><strong class="blue">Date:</strong></td>
                            <td width="80%"><?php echo $order->created_at->formatLocalized('%d %B %Y'); ?></td>
                        </tr>
                        <tr>
                            <td width="28%"><strong class="blue">N° facture:</strong></td>
                            <td width="72%"><?php echo $order->order_no; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table id="invoice-table">
            <thead>
                <tr>
                    <th width="7%" class="text-left">Qt</th>
                    <th width="53%" class="text-left">Nom de l'ouvrage</th>
                    <th width="20%" class="text-right">Prix à l'unité</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(!empty($products))
                {
                    foreach($products as $product_id => $product)
                    {
                        $qty = $product->count();
                        echo '<tr>';
                            echo '<td class="text-left">'.$qty.'</td>';
                            echo '<td class="text-left">'.$product->first()->title.'</td>';
                            echo '<td class="text-right">'.$product->first()->price_cents.' <span>CHF</span></td>';
                            $subtotal = $product->first()->price_cents * $qty;
                            echo '<td class="text-right">'. number_format((float)$subtotal, 2, '.', '').' <span>CHF</span></td>';
                        echo '</tr>';
                    }
                }
            ?>

            </tbody>
        </table>

        <table id="content-table">
            <tr><td colspan="2" height="5">&nbsp;</td></tr>
            <tr>
                <!-- Messages for customer -->
                <td width="62%" align="top" valign="top">

                    <h3>Communications</h3>
                    <div class="communications">
                    <?php
                        if(!empty($messages))
                        {
                            foreach($msgTypes as $msgType)
                            {
                                if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                                {
                                    echo '<p class="message '.$msgType.'">'.$messages[$msgType].'</p>';
                                }
                            }
                        }
                    ?>
                        <p class="message"><?php echo $messages['remerciements']; ?></p><br/>
                        <p class="message">Neuchâtel, le <?php echo $date; ?></p>
                    </div>

                </td>
                <td width="5%" align="top" valign="top"></td>
                <!-- Total calculations -->
                <td width="33%" align="top" valign="top" class="text-right">
                    <table width="100%" id="content-table" class="total_line" align="right" valign="top">
                        <?php
                        if($order->coupon_id > 0)
                        {
                            echo '<tr align="top" valign="top">';
                            if( $order->coupon->type == 'shipping')
                            {
                                echo '<td width="40%" align="top" valign="top" class="text-right">Frais de port offerts</td>';
                                echo '<td width="60%" align="top" valign="top" class="text-right"></td>';
                            }
                            else
                            {
                                echo '<td width="40%" align="top" valign="top" class="text-right text-muted">Rabais '.$order->coupon->title.'</td>';
                                echo '<td width="60%" align="top" valign="top" class="text-right"> -'.$order->coupon->value.'%</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right"><strong>Sous-total:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right"><?php echo $order->price_cents; ?> CHF</td>
                        </tr>
                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right"><strong>Frais de port:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right"><?php echo $order->shipping->price_cents; ?> CHF</td>
                        </tr>
                        <tr align="top" valign="top">
                            <td width="40%" align="top" valign="top" class="text-right line"><strong>Total:</strong></td>
                            <td width="60%" align="top" valign="top" class="text-right line"><strong><?php echo $order->total_with_shipping; ?> CHF</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- BV id payment type = 1 -->
    <?php if($order->payement_id == 1){ ?>
        <?php
            if($products->count() > 7){
                echo '<p style="page-break-after: always;"></p>';
            }

            list($francs,$centimes) = $order->price_total_explode;
        ?>

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
                                        foreach($versement as $line){
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
                                        echo '<li>'.$motif['centre'].'</li>';
                                        echo '<li>'.$motif['texte'].'</li>';
                                        echo '<li>Facture N° '.$order->order_no.'</li>';
                                    echo '</ul>';
                                ?>
                            </td>
                            <td align="top" valign="top" width="32%" height="20mm"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <?php } ?>

</body>
</html>