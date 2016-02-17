<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace CoreTest\Auth;

use CoreTest\Auth\AuthTest;

/**
 * Testing Auth-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class AuthBasicStatelessTest extends AuthTest
{
	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		$_ENV['APP_STATELESS'] = true;
		$_ENV['APP_AUTH_GUARD'] = 'basic';

		return parent::createApplication();
	}
}
