<html>
<head>
    <style type="text/css">
        @page { margin: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        h1{
            font-size: 20px;
            font-weight: 600;
            display: block;
        }
        h3{
            font-size: 14px;
            display: block;
            font-weight: normal;
            color: #000;
            line-height: 25px;
            margin: 0;
            padding: 0;
            padding-bottom: 10px;
        }
        #content{
            margin: 8mm 10mm;
            display: block;
            width: 100%;
            z-index: 2;
        }

        .title{
            margin-bottom: 10px;
        }
        /*
        * Table for invoice
        */
        #invoice-table,
        #content-table
        {
            font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
            font-size: 12px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }
        #invoice-table{
            margin-top: 20px;
        }
        #invoice-table tr,
        {
            font-size: 12px;
        }
        #invoice-table th
        {
            padding: 5px 2px;
            font-weight: bold;
            color: #000;
            border-bottom: 2px solid #004c7c;
        }
        #invoice-table td
        {
            padding: 6px 2px 6px 2px;
            border-bottom: 1px solid #c3c3c3;
            color: #4d4d4d;
        }

        /*
        * Adresse
        */

        #facdroit,#user,#tva{
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #user li,#tva li{
            font-size: 12px;
        }

        #facdroit li, .facdroit li{
            font-size: 11px;
        }

        .facdroit{
            padding: 40px 20px 0px 20px;
            list-style: none;
            margin: 0;
        }

        #tva li,.infos td{
            font-size: 10px;
            line-height: 14px;
        }

        .misc-infos{
            padding: 10px;
            background: #f5f5f5;
            margin-top: 15px;
        }

        /*
        * Colors
        */
        .blue{
            color: #004c7c;
        }

        /*
        * Textes
        */

        .text-left{
            text-align: left;
        }

        .text-right{
            text-align: right;
        }

        .text-middle{
            text-align: center;
        }

        .total_line td{
            line-height: 20px;
            padding-right: 2px;
        }

        .total_line td strong{
            margin: 0;
            padding: 0;
            vertical-align: top;
        }
        
        .line{
            border-top: 1px solid #e5e5e5;
            background: #f5f5f5;
            padding-top: 5px;
        }
        
        /*
        * Messages
        */
        .communications{
            display: block;
            margin: 5px 0;
        }

        .warning{
            color: #b80f1d;
        }

        .special{
            color: #056734;
        }

        .message{
            margin: 3px 0;
            display: block;
        }

        .signature{
            margin-top: 20px;
        }

        /*
        * BV
        */

        #bv-table{
            position: absolute;
            height: 106mm;
            width: 210mm;
            z-index: 3;
            bottom: 0;
            left: 0;
            font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
            font-size: 12px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            background-image: url("<?php echo asset('images/bvr.jpg'); ?>");
        }

        #recu{
            margin: 0;
            padding: 0;
            width: 60mm;
            height: 106mm;
            border-collapse: collapse;
        }

        #recu td{
            margin: 0;
        }

        #compte{
            width: 62mm;
            height: 106mm;
            border-collapse: collapse;
        }

        #versement{
            width: 88mm;
            height: 106mm;
            border-collapse: collapse;
        }

        .compte{
            font-weight: bold;
            padding-left: 105px;
        }

        .price{
            text-align: right;
            padding-right: 4px;
            font-size: 17px;
            letter-spacing:2.07mm;
        }

        .francs{
            margin-right: 6.5mm;
            letter-spacing:2.08mm;
        }

        #recu .francs{
            margin-right: 6.2mm;
            letter-spacing:2.08mm;
        }

        #compte .price{
            padding-right: 5px;
        }

    </style>
</head>
<body style="position: relative;height:297mm;">
    <div id="content">
        <table id="content-table">
            <tr>
                <td colspan="2"><img height="90mm" src="<?php echo public_path('files/logos/facdroit.jpg'); ?>" alt="Unine logo" /></td>
            </tr>
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    <?php
                    if(!empty($facdroit)){
                        echo '<ul id="facdroit">';
                            foreach($facdroit as $line){
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
                            echo (!empty($adresse->cp) ? '<li>'.$adresse->cp.'</li>' : '');
                            echo '<li>'.$adresse->npa.' '.$adresse->ville.'</li>';
                            echo '</ul>';
                        }

                    ?>
                </td>
            </tr>
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
        </table>

        <h1 class="title blue">Facture</h1>

        <table id="content-table">
            <tr>
                <td width="59%" align="top" valign="top"  class="misc-infos">
                    <ul id="tva">
                        <li>N° CHE-115.251.043TVA</li>
                        <li>Taux 2.5% inclus pour les livres</li>
                        <li>Taux 8% pour les autres produits</li>
                    </ul>
                </td>
                <td width="1%" align="top" valign="top"></td>
                <td width="40%" align="top" valign="top" class="misc-infos">
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
            <tr>
                <td class="text-left">1</td>
                <td>Title of your article here</td>
                <td class="text-right">
                    200.00 <span>CHF</span>
                </td>
                <td class="text-right">200.00  <span>CHF</span></td>
            </tr>
            <tr>
                <td class="text-left">10</td>
                <td>Details of project here</td>
                <td class="text-right">75.00  <span>CHF</span></td>
                <td class="text-right">750.00 <span>CHF</span></td>
            </tr>
            <tr>
                <td class="text-left">1345</td>
                <td>WordPress Blogging theme</td>
                <td class="text-right">50.00 <span>CHF</span></td>
                <td class="text-right">250.00 <span>CHF</span></td>
            </tr>

            </tbody>
        </table>

        <table id="content-table">
            <tr><td colspan="2" height="15">&nbsp;</td></tr>
            <tr>
                <!-- Messages for customer -->
                <td width="62%" align="top" valign="top">
                    <?php

                        if(!empty($messages))
                        {
                            echo '<h3>Communications</h3>';
                            echo '<div class="communications">';

                            foreach($msgTypes as $msgType)
                            {
                                if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                                {
                                    echo '<p class="message '.$msgType.'">'.$messages[$msgType].'</p>';
                                }
                            }
                            echo '</div>';
                        }

                    echo '<p>Neuchâtel, le '.$date.'</p>';

                    ?>
                </td>
                <td width="5%" align="top" valign="top"></td>
                <!-- Total calculations -->
                <td width="33%" align="top" valign="top" class="text-right">
                    <table width="100%" id="content-table" class="total_line" align="right" valign="top">
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
    
    <table id="bv-table">
        <tr align="top" valign="top">
            <td width="60mm" align="top" valign="top">
                <table id="recu" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <ul class="facdroit">
                                <li>Université de Neuchâtel</li>
                                <li>Séminaire sur le droit du bail</li>
                                <li>2000 Neuchâtel</li>
                            </ul>
                        </td>
                    </tr>
                    <tr><td align="top" valign="center" height="7.6mm" class="compte">20-4130-2</td></tr>
                    <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs">123</span>00</td></tr>
                </table>
            </td>
            <td width="62mm" align="top" valign="top">
                <table id="compte" valign="top">
                    <tr>
                        <td align="top" valign="center" height="43mm">
                            <ul class="facdroit">
                                <li>Université de Neuchâtel</li>
                                <li>Séminaire sur le droit du bail</li>
                                <li>2000 Neuchâtel</li>
                            </ul>
                        </td>
                    </tr>
                    <tr><td align="top" valign="top" height="7.6mm" class="compte">20-4130-2</td></tr>
                    <tr><td align="top" valign="top" height="6mm" class="price"><span class="francs">123</span>00</td></tr>
                </table>
            </td>
            <td width="88mm" align="top" valign="top">
                <table id="versement" valign="top">
                    <tr>
                        <td align="top" valign="top" width="64%" height="20mm">
                            <ul class="facdroit">
                                <li>U. 01852</li>
                                <li>Vente ouvrages</li>
                                <li>Facture N° 2013-00000242</li>
                            </ul>
                        </td>
                        <td align="top" valign="top" width="32%" height="20mm"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


</body>
</html>