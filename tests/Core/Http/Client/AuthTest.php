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

namespace CoreTest;

use Core\Bases\Tests\BaseTest;
use Core\Http\Client\Auth;
use Core\Http\Request;
use Core\Models\Auth\LoginAttempt;
use Core\Models\Auth\PasswordReset;
use Core\Models\User;

/**
 * Testing Auth-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class AuthTest extends BaseTest
{
	/**
	 * The email to use for the test user
	 * @var string
	 */
	protected $email = 'testtset12344321@test.com';

	/**
	 * The password to user for authentication
	 * @var string
	 */
	protected $password = 'secret';

	/**
	 * Test Login Credentials
	 */
	public function testLogin()
	{
		$auth = Auth::getInstance();
		$user = $this->createUser();

		// login
		$this->assertTrue(!!$auth->login($this->email, $this->password));
		$this->assertTrue($auth->loggedIn);
		$this->assertTrue($auth->user->email == $this->email);

		// logout
		$auth->logout();
		$this->assertTrue(!$auth->loggedIn);
		$this->assertTrue($auth->user == null);
	}

	/**
	 * Test set user
	 */
	public function testSetUser()
	{
		$auth = Auth::getInstance();
		$user = $this->createUser();

		// login, fetch token
		$auth->setUser($user);
		$this->assertTrue($auth->loggedIn);

		// logout
		$auth->logout();
		$this->assertTrue(!$auth->loggedIn);
	}

	/**
	 * Test Login attempts
	 */
	public function testLoginAttempts()
	{
		$auth = Auth::getInstance();
		$user = $this->createUser();

		$this->assertTrue($auth->loginAttempts === 0);

		// try login
		$this->assertTrue(!$auth->login($this->email, 'notthepassword'));
		$this->assertTrue(!$auth->loggedIn);
		$this->assertTrue($auth->loginAttempts === 1);

		// try login
		$this->assertTrue(!$auth->login($this->email, 'notthepassword'));
		$this->assertTrue(!$auth->loggedIn);
		$this->assertTrue($auth->loginAttempts === 2);
	}

	/**
	 * Test Password Reset Token
	 */
	public function testPasswordResetToken()
	{
		$auth = Auth::getInstance();
		$user = $this->createUser();

		$token = $auth->getPasswordResetToken($user->email);
		$this->assertTrue($token != null);

		$resetEmail = PasswordReset::where('token', '=', $token)->first()->email;
		$this->assertTrue($resetEmail == $user->email);
	}

	/**
	 * Create a new fresh user to work with
	 * @return User
	 */
	protected function createUser()
	{
		$user = call_user_func(config('app.auth.provider.model') . '::dummy');

		$user->id = null;
		$user->email = $this->email;
		$user->password = bcrypt($this->password);

		$user->save();

		return $user;
	}
}
