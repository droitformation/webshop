<?php

/*
|--------------------------------------------------------------------------
| Jobs reminders
|--------------------------------------------------------------------------
*/

return [

    'product'    => [
        'name'  => 'Livres',
        'model' => 'App\Droit\Shop\Product\Entities\Product',
        'dates' => ['created_at' => 'Date de création']
    ],
    'colloque' => [
        'name'  => 'Colloques',
        'model' => 'App\Droit\Colloque\Entities\Colloque',
        'dates' => [
            'start_at'        => 'Date de début',
            'end_at'          => 'Date de fin',
            'registration_at' => 'Délai d\'inscription'
        ]
    ]

];
