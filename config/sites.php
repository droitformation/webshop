<?php

/* ==========================
* Sites functionalities
=============================*/

return [
    'pubdroit' => [
       'site' => [
           ['name' => 'Menus', 'url' => 'menus', 'color' => 'sky', 'icon' => 'list'],
           ['name' => 'Contenus', 'url' => 'blocs', 'color' => 'primary', 'icon' => 'clipboard'],
           ['name' => 'Pages', 'url' => 'pages', 'color' => 'orange', 'icon' => 'file-text'],
        ]
    ],
    'bail' => [
        'site' => [
            ['name' => 'Menus', 'url' => 'menus','color' => 'sky', 'icon' => 'list'],
            ['name' => 'Contenus', 'url' => 'blocs','color' => 'primary', 'icon' => 'clipboard'],
            ['name' => 'Pages', 'url' => 'pages','color' => 'orange', 'icon' => 'file-text'],
            ['name' => 'Arrêts', 'url' => 'arrets','color' => 'green', 'icon' => 'edit'],
            ['name' => 'Analyses', 'url' => 'analyses','color' => 'brown', 'icon' => 'dot-circle-o'],
            ['name' => 'Catégories', 'url' => 'categories','color' => 'inverse', 'icon' => 'edit'],
        ],
        'general' => [
            ['name' => 'Séminaires', 'url' => 'seminaire','color' => 'midnightblue', 'icon' => 'sticky-note'],
            ['name' => 'Calculette IPC', 'url' => 'calculette/ipc','color' => 'magenta', 'icon' => 'sort-numeric-asc'],
            ['name' => 'Calculette Taux', 'url' => 'calculette/taux','color' => 'sky', 'icon' => 'percent'],
        ]
    ],
    'matrimonial' => [
        'site' => [
            ['name' => 'Menus', 'url' => 'menus','color' => 'sky', 'icon' => 'list'],
            ['name' => 'Contenus', 'url' => 'blocs','color' => 'primary', 'icon' => 'clipboard'],
            ['name' => 'Pages', 'url' => 'pages','color' => 'orange', 'icon' => 'file-text'],
            ['name' => 'Arrêts', 'url' => 'arrets','color' => 'green', 'icon' => 'edit'],
            ['name' => 'Analyses', 'url' => 'analyses','color' => 'brown', 'icon' => 'dot-circle-o'],
            ['name' => 'Catégories', 'url' => 'categories','color' => 'inverse', 'icon' => 'edit'],
        ]
    ],
    'droitdutravail' => [
        'site' => [
            ['name' => 'Menus', 'url' => 'menus','color' => 'sky', 'icon' => 'list'],
            ['name' => 'Contenus', 'url' => 'blocs','color' => 'primary', 'icon' => 'clipboard'],
            ['name' => 'Pages', 'url' => 'pages','color' => 'orange', 'icon' => 'file-text'],
            ['name' => 'Arrêts', 'url' => 'arrets','color' => 'green', 'icon' => 'edit'],
            ['name' => 'Analyses', 'url' => 'analyses','color' => 'brown', 'icon' => 'dot-circle-o'],
            ['name' => 'Catégories', 'url' => 'categories','color' => 'inverse', 'icon' => 'edit'],
        ]
    ],
    'rcassurances' => [
        'site' => [
            ['name' => 'Menus', 'url' => 'menus','color' => 'sky', 'icon' => 'list'],
            ['name' => 'Contenus', 'url' => 'blocs','color' => 'primary', 'icon' => 'clipboard'],
            ['name' => 'Pages', 'url' => 'pages','color' => 'orange', 'icon' => 'file-text'],
            ['name' => 'Arrêts', 'url' => 'arrets','color' => 'green', 'icon' => 'edit'],
            ['name' => 'Analyses', 'url' => 'analyses','color' => 'brown', 'icon' => 'dot-circle-o'],
            ['name' => 'Catégories', 'url' => 'categories','color' => 'inverse', 'icon' => 'edit'],
        ]
    ],
    'fac_sites' => [
        'pubdroit'         => ['image' => 'pubdroit.png', 'url' => 'http://www.publications-droit.ch'],
        'bail'             => ['image' => 'bail.png', 'url' => 'http://www.bail.ch'],
        'matrimonial'      => ['image' => 'matrimonial.png', 'url' => 'http://www.droitmatrimonial.ch'],
        'droitpraticien'   => ['image' => 'droitpraticien.png', 'url' => 'http://www.droitpraticien.ch'],
        'tribunauxcivils'  => ['image' => 'tribunaux.png', 'url' => 'http://www.tribunauxcivils.ch'],
        'droitenschemas'   => ['image' => 'schemas.png', 'url' => 'http://www.droitenschemas.ch'],
        'droitdutravail'   => ['image' => 'droittravail.png', 'url' => 'http://www.droitdutravail.ch'],
        'rjne'             => ['image' => 'rjn.png', 'url' => 'http://www.rjne.ch'],
        'rcassurances'     => ['image' => 'rca.png', 'url' => 'http://www.rcassurances.ch'],
    ]
];