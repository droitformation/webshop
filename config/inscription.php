<?php

/*
|--------------------------------------------------------------------------
| Inscriptions config for frontend
|--------------------------------------------------------------------------
*/

return [

    'messages' => [
        'pending'    => '<p>Vous avez des payements en attente, veuillez contacter le sécrétariat <a href="mailto:droit.formation@unine.ch">droit.formation@unine.ch</a> </p>',
        'registered' => '<p>Vous êtes déjà inscrit à ce colloque.</p>'
    ],
    'days' => 40,
    'link' => env('EMBED_LINK',false)

];