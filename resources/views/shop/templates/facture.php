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
        #content{
            margin: 8mm 10mm;
            display: block;
            width: 100%;;
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
            font-weight: normal;
            color: #000;
            border-bottom: 1px solid #004c7c;
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

        #facdroit li{
            font-size: 11px;
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

    </style>
</head>
<body>
    <div id="content">
        <table id="content-table">
            <tr>
                <td colspan="2"><img height="80mm" src="<?php echo public_path('files/logos/facdroit.jpg'); ?>" alt="Unine logo" /></td>
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
            /*        if(!empty($user)){
                        echo '<ul id="user">';
                        foreach($user as $line){
                            echo '<li>'.$line.'</li>';
                        }
                        echo '</ul>';
                    }*/
                    ?>
                </td>
            </tr>
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
                    <th width="8%" class="text-middle">Qt</th>
                    <th width="52%" class="text-left">Description</th>
                    <th width="20%" class="text-right">Prix à l'unité</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-middle">-</td>
                <td>Title of your article here</td>
                <td class="text-right">
                    200.00 <span>CHF</span>
                </td>
                <td class="text-right">200.00  <span>CHF</span></td>
            </tr>
            <tr>
                <td class="text-middle">10</td>
                <td>Details of project here</td>
                <td class="text-right">75.00  <span>CHF</span></td>
                <td class="text-right">750.00 <span>CHF</span></td>
            </tr>
            <tr>
                <td class="text-middle">1345</td>
                <td>WordPress Blogging theme</td>
                <td class="text-right">50.00 <span>CHF</span></td>
                <td class="text-right">250.00 <span>CHF</span></td>
            </tr>
            </tbody>
        </table>

    </div>
</body>
</html>