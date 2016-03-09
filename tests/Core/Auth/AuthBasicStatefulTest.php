<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace CoreTest\Auth;

use CoreTest\Auth\AuthTest;

/**
 * Testing Auth-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class AuthBasicStatefulTest extends AuthTest
{
	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		$_ENV['APP_STATEFUL'] = true;
		$_ENV['APP_AUTH_GUARD'] = 'basic';

		return parent::createApplication();
	}
}
