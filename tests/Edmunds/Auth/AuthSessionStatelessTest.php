<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace EdmundsTest\Auth;

use Edmunds\Auth\Auth;
use Edmunds\Bases\Tests\BaseTest;
use Exception;

/**
 * Testing Auth-class
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
