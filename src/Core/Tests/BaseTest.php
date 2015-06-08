<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Tests;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Mail;

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

	/**
	 * Constructor
	 */
	public function __construct()
	{

	}

	/**
	 * Creates the application.
	 */
	public function createApplication()
	{

	}

	/**
	 * Set up the test
	 */
	public function setUp()
	{
		parent::setUp();

		$this->prepareForTests();
	}

	/**
	 * Migrates the database and set the mailer to 'pretend'.
	 * This will cause the tests to run quickly.
	 */
	private function prepareForTests()
	{
		//Artisan::call('migrate');
		Mail::pretend(true);
	}

}
