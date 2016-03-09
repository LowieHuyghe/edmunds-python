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

use Core\Auth\Auth;
use Core\Bases\Tests\BaseTest;
use Exception;

/**
 * Testing Auth-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class AuthSessionStatelessTest extends BaseTest
{
	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		$_ENV['APP_STATEFUL'] = false;
		$_ENV['APP_AUTH_GUARD'] = 'session';

		return parent::createApplication();
	}

	/**
	 * Test exception when stateless session
	 */
	public function testStatelessSession()
	{
		$exception = false;

		try
		{
			$auth = Auth::getInstance();
			$user = $this->createUser();

			$auth->login('email@example.com', 'elpmaxe');
		}
		catch (Exception $e)
		{
			$exception = true;
		}

		$this->assertTrue($exception);
	}

	/**
	 * Create a new fresh user to work with
	 * @return User
	 */
	protected function createUser()
	{
		$user = call_user_func(config('app.auth.models.user') . '::dummy');

		$user->id = null;
		$user->email = $this->email;
		$user->password = bcrypt($this->password);

		$user->save();

		return $user;
	}
}
