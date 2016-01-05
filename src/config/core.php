<?php

return array
(

	'config' => array(
		'required' => array(
			'app.name',
			'app.key',
			'app.cipher',
			'routing.namespace',
			'routing.defaultcontroller',
			'routing.namespace',
			'routing.loginroute',
			'analytics.ga.version',
			'analytics.ga.trackingid',
		),
	),

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

	'auth' => array(
		'ttl' => array(
			'passwordreset' => 15,
			'authtoken' => 24,
		),
	),

	'localization' => array(
		'locale' => array(
			'default' => 'en',
			'fallback' => 'en',
		),
		'language' => array(
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