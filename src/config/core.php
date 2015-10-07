<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <LowieHuyghe@users.noreply.github.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

/**
 * A configuration file
 *
 * @author		Lowie Huyghe <LowieHuyghe@users.noreply.github.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

return array(

	'config' => [
		'required' => [
			'app.specs.sitename',

			'app.routing.namespace',
			'app.routing.default',
			'app.routing.home',
			'app.routing.loginroute',
			'app.routing.redirecthalt',
		],
	],

	'pm' => [
		'pushbullet' => [
			'account' => 'LowieHuyghe@users.noreply.github.com',
			'token' => 'jue2B1zhgiU6cIwxAc55vY0rKfjL5iC5',
		],
	],

);