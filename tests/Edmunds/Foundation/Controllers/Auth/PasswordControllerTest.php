<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace EdmundsTest\Foundation\Controllers\Auth;

use Edmunds\Auth\Auth;
use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Foundation\Controllers\Auth\PasswordController;

/**
 * Testing Auth-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */
class PasswordControllerTest extends BaseTest
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
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		PasswordController::registerRoutes($this->app);
	}

	/**
	 * Test post reset
	 */
	public function testPostReset()
	{
		$user = $this->createUser();
		$token = app('auth.password')->broker(null)->getRepository()->create($user);
		$newPassword = 'othersecret';

		$response = $this->call('POST', '/password/reset', ['email' => $this->email, 'token' => $token, 'password' => $newPassword, 'password_confirmation' => $newPassword]);

		$this->assertTrue($response instanceof \Symfony\Component\HttpFoundation\RedirectResponse);
		$this->assertTrue($this->getRedirectionPath($response) == config('app.auth.redirects.login'));
	}

	/**
	 * Get the path
	 * @param  Illuminate\Http\RedirectResponse $redirect
	 * @return string
	 */
	protected function getRedirectionPath($redirect)
	{
		return parse_url($redirect->getTargetUrl(), PHP_URL_PATH) ?: '/';
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
