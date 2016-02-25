<?php

return array
(
	/*
	|--------------------------------------------------------------------------
	| System
	|--------------------------------------------------------------------------
	|
	| Set the configuration for the server.
	|
	*/

	'system' => array(
		'timezone' => 'UTC',
	),

	/*
	|--------------------------------------------------------------------------
	| Configuration
	|--------------------------------------------------------------------------
	|
	| Here is chosen which configuration is required in order to run the
	| application.
	|
	*/

	'config' => array(
		'required' => array(
			'app.name',
			'app.key',
			'app.cipher',

			'app.routing.routes',
		),
	),


	/*
	|--------------------------------------------------------------------------
	| Admin
	|--------------------------------------------------------------------------
	|
	| Set configuration to use when contacting the admin through slack
	| or similar channels.
	|
	*/

	'admin' => array(
		'pm' => array(
			'default' => 'slack',

			'slack' => array(
				'hook' => 'https://hooks.slack.com/services/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',
				'icon' => ':sheep:',
				'channel' => array(
					'info' => '#dev-info',
					'warning' => '#dev-warning',
					'error' => '#dev-error',
				),
			),
		),
	),


	/*
	|--------------------------------------------------------------------------
	| Authentication
	|--------------------------------------------------------------------------
	|
	| Set configuration to use for authentication. The Time-To-Live for
	| password-reset or authentication-tokens can be set.
	|
	*/

	'auth' => array(
		'ttl' => array(
			'passwordreset' => 60,
		),
	),


	/*
	|--------------------------------------------------------------------------
	| Localization
	|--------------------------------------------------------------------------
	|
	| Configuration for localization can be set and customized here.
	|
	*/

	'localization' => array(

		'locale' => array(
			'default' => 'en',
			'fallback' => 'en',
			'direction' => array(
				'default' => 'ltr',
				'languages' => array(
					'ar' => 'rtl',		// Arabic
					// 'arc' => 'rtl',	// Aramaic
					// 'bcc' => 'rtl',	// Southern Balochi
					// 'bqi' => 'rtl',	// Bakthiari
					// 'ckb' => 'rtl',	// Sorani Kurdish
					'dv' => 'rtl',		// Dhivehi
					'fa' => 'rtl',		// Persian
					// 'glk' => 'rtl',	// Gilaki
					'he' => 'rtl',		// Hebrew
					// 'lrc' => 'rtl',	// Northern Luri
					// 'mzn' => 'rtl',	// Mazanderani
					// 'pnb' => 'rtl',	// Western Punjabi
					'ps' => 'rtl',		// Pashto
					'sd' => 'rtl',		// Sindhi
					'ug' => 'rtl',		// Uyghur
					'ur' => 'rtl',		// Urdu
					'yi' => 'rtl',		// Yiddish
				),
			),
		),

		'currency' => array(
			'default' => 'EUR',
		),

		'timezone' => array(
			'default' => 'Europe/Brussels',
		),

		'measurement' => array(
			'default' => 'metric',
			'imperial' => array(
				'countries' => array(
					'LR',	// Liberia
					'US',	// America
					'MM', 	// Myanmar
				),
			),
		),

	),


	/*
	|--------------------------------------------------------------------------
	| Keys
	|--------------------------------------------------------------------------
	|
	| The keys used when saving information client-side
	|
	*/

	'keys' => array(
		'visitor' => array(
			'id' => array(
				'key' => 'visitor_id',
				'header' => 'X-Visitor-Id',
			),
			'localization' => array(
				'general' => 'visitor_localization',

				'locale' => 'X-Localization-Locale',
				'currency' => 'X-Localization-Currency',
				'timezone' => 'X-Localization-Timezone',
			),
			'location' => array(
				'general' => 'visitor_location',
			),
		),
	),

);