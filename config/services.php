<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\Shop\User\Entities\User',
		'secret' => env('STRIPE_API_SECRET'),
	],

    'rollbar' => [
        'access_token' => 'e2cdbd5bcb514f89ac4ae5a53fa05cbf',
        'level'        => 'debug',
        'environment'  => 'local',
    ],

    'github' => [
        'client_id'     => '9d2f63dc1cf12bf5ec8e',
        'client_secret' => '9b4bc3765e6a1bdeb3ed4b84a3bc2f845df935da',
        'redirect'      => 'http://shop.local/auth/github/callback',
    ],

    'droithub' => [
        'client_id'     => '1',
        'client_secret' => 'droitformation',
        'redirect'      => 'http://shop.local/login/droithub', // http://hub.local/login/droithub
    ]

];
