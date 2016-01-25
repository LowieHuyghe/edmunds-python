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
use Core\Http\Client\Visitor;
use Core\Models\User;

/**
 * Testing Visitor-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
		$user = User::dummy();
		$user->id = null;
		$user->save();

		// logged in
		Auth::getInstance()->loginWithUser($user);
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
		$user = User::dummy();
		$user->id = null;
		$user->save();

		// logged in
		Auth::getInstance()->loginWithUser($user);
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

}
