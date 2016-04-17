<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Tests;

use Edmunds\Http\Client\Session;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

/**
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 *
 * @method void tearDown Tear down the test environment
 *
 */
class BaseTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		return require APP_BASE_PATH . '/bootstrap/app.php';
	}

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		if ($this->app->isStateful())
		{
			Session::getInstance()->start();
		}
	}
}
