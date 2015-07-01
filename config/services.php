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

];
