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

namespace Core\Bases\Tests;

use Core\Http\Client\Session;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

/**
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
		return require __DIR__ . '/../../../bootstrap/core.php';
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
