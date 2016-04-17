<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace EdmundsTest;

use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Auth\Auth;
use Edmunds\Http\Client\Visitor;
use Edmunds\Auth\Models\User;

/**
 * Testing Visitor-class
 */
class VisitorTest extends BaseTest
{
	/**
	 * Test Id
	 */
	public function testId()
	{
		$id = Visitor::getInstance()->id;

		$this->assertTrue($id != null);
	}

	/**
	 * Test User
	 */
	public function testUser()
	{
		// not logged in
		Auth::getInstance()->logout();
		$this->assertTrue(Visitor::getInstance()->user == null);

		// make user
		$user = $this->createUser();

		// logged in
		Auth::getInstance()->setUser($user);
		$this->assertTrue(Visitor::getInstance()->user != null);

		// logged out
		Auth::getInstance()->logout();
		$this->assertTrue(Visitor::getInstance()->user == null);
	}

	/**
	 * Test LoggedIn
	 */
	public function testLoggedIn()
	{
		// not logged in
		Auth::getInstance()->logout();
		$this->assertTrue(!Visitor::getInstance()->loggedIn);

		// make user
		$user = $this->createUser();

		// logged in
		Auth::getInstance()->setUser($user);
		$this->assertTrue(Visitor::getInstance()->loggedIn);

		// logged out
		Auth::getInstance()->logout();
		$this->assertTrue(!Visitor::getInstance()->loggedIn);
	}

	/**
	 * Test Context
	 */
	public function testContext()
	{
		$this->assertTrue(Visitor::getInstance()->context != null);
	}

	/**
	 * Test Localization
	 */
	public function testLocalization()
	{
		$this->assertTrue(Visitor::getInstance()->localization != null);
	}

	/**
	 * Test Location
	 */
	public function testLocation()
	{
		$this->assertTrue(Visitor::getInstance()->location != null);
	}

	/**
	 * Create a new fresh user to work with
	 * @return User
	 */
	protected function createUser()
	{
		$user = call_user_func(config('app.auth.models.user') . '::dummy');

		$user->id = null;

		$user->save();

		return $user;
	}
}
