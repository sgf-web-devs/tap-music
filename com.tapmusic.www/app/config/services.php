<?php

return array(

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

	'mailgun' => array(
		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

    'pusher' => array(
        'app_id' => getenv('PUSHER_APP_ID'),
        'app_key' => getenv('PUSHER_APP_KEY'),
        'app_secret' => getenv('PUSHER_APP_SECRET')
    ),

    'spotify' => array(
        'client_id' => getenv('SPOTIFY_CLIENT_ID'),
        'client_secret' => getenv('SPOTIFY_CLIENT_SECRET'),
        'callback_url' => Request::root().getenv('SPOTIFY_CALLBACK_URL')
    ),

);
