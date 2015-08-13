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
                <td colspan="2"><img height="80mm" src="<?php echo public_path('files/logos/facdroit.png'); ?>" alt="Unine logo" /></td>
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
                        $adresse = $inscription->user->adresse_facturation;

                        if($adresse)
                        {
                            echo '<ul id="user">';
                            echo (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '');
                            echo '<li>'.$adresse->civilite_title.' '.$inscription->user->name.'</li>';
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
        <h1 class="title blue">BON DE PARTICIPATION <?php echo $inscription->inscription_no; ?></h1>
        <p class="red"><small>A présenter lors de votre arrivée</small></p>

        <table class="content-table content-wide" valign="top">
            <tr valign="top">
                <td valign="top">
                    <p class="organisateur"><strong>Organisé par:</strong> <?php echo $inscription->colloque->organisateur; ?></p>
                </td>
            </tr>
            <tr><td height="5">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top">
                    <h2><?php echo $inscription->colloque->titre; ?></h2>
                    <h3><?php echo $inscription->colloque->soustitre; ?></h3>
                </td>
            </tr>
            <tr><td height="5">&nbsp;</td></tr>
            <tr>
                <td valign="top">
                    <h3 class="titre-info"><strong>Date:</strong> <?php echo $inscription->colloque->event_date; ?></h3>
                    <h3 class="titre-info"><strong>Lieu:</strong> <?php echo $inscription->colloque->location->name.', '.$inscription->colloque->location->adresse; ?></h3>
                </td>
            </tr>
            <tr><td height="5">&nbsp;</td></tr>

            <?php
                if(!$inscription->user_options->isEmpty())
                {
                    echo '<tr><td valign="top">';
                    echo '<h4>Choix:</h4>';
                    echo '<ul class="options">';

                    foreach($inscription->user_options as $user_options)
                    {
                        echo '<li>'.$user_options->option->title;

                        if($user_options->option->type == 'choix')
                        {
                            $user_options->load('option_groupe');
                            echo '<li class="blue text-indent">'.$user_options->option_groupe->text.'</li>';
                        }
                        echo '</li>';
                    }

                    echo '<ul>';
                    echo '</td></tr>';
                }
            ?>

        </table>
    </div>

   <?php if($inscription->colloque->location->location_map){ ?>
    <div class="content">
        <table class="content-table" valign="top">
            <tr><td height="25">&nbsp;</td></tr>
            <tr valign="top">
                <td valign="top" align="center">
                    <img style="max-width: 120mm" src="<?php echo public_path($inscription->colloque->location->location_map); ?>" alt="Carte" />
                </td>
            </tr>
        </table>
    </div>
    <?php } ?>

</body>
</html>