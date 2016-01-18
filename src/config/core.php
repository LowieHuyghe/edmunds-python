<?php

return array
(

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

			'app.routing.namespace',
			'app.routing.defaultcontroller',
			'app.routing.namespace',
			'app.routing.loginroute',

			'app.analytics.piwik.version',
			'app.analytics.piwik.siteid',
			'app.analytics.piwik.token',

			'app.analytics.newrelic.appname',
			'app.analytics.newrelic.license',

			'app.localization.locale.default',
			'app.localization.locale.fallback',
			'app.localization.locale.accepted',
			'app.localization.currency.default',
			'app.localization.currency.accepted',
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
			'driver' => 'slack',

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
			'passwordreset' => 15,
			'authtoken' => 24,
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

	),

);