<?php

/*
|--------------------------------------------------------------------------
| Inscriptions config for frontend
|--------------------------------------------------------------------------
*/

return [

    'names' => [
        'civilite_title'   => 'Civilité',
        'first_name'       => 'Prénom',
        'last_name'        => 'Nom',
        'email'            => 'E-mail',
        'profession_title' => 'Profession',
        'company'          => 'Entrprise',
        'telephone'        => 'Téléphone',
        'mobile'           => 'Mobile',
        'adresse'          => 'Adresse',
        'cp_trim'          => 'CP',
        'complement'       => 'Complément d\'adresse',
        'npa'              => 'NPA',
        'ville'            => 'Ville',
        'canton_title'     => 'Canton',
        'pays_title'       => 'Pays'
    ],

    'types' => [
        'text'     => ['title' => 'Texte', 'color' => 'primary'],
        'lois'     => ['title' => 'Loi', 'color' => 'success'],
        'autorite' => ['title' => 'Autorité', 'color' => 'magenta'],
        'lien'     => ['title' => 'Lien', 'color' => 'orange'],
        'faq'      => ['title' => 'Faq', 'color' => 'green'],
    ]

];