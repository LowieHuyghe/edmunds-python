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

namespace CoreTest\Io\Admin;

use Core\Bases\Tests\BaseTest;
use Core\Registry;

/**
 * Testing Pm-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PmTest extends BaseTest
{

	/**
	 * Test Info
	 */
	public function testInfo()
	{
		$success = Registry::pm()->info('Info-Title', "The body of the info");

		$this->assertTrue($success);
	}

	/**
	 * Test Warning
	 */
	public function testWarning()
	{
		$success = Registry::pm()->warning('Warning-Title', "The body of the warning");

		$this->assertTrue($success);
	}

	/**
	 * Test Error
	 */
	public function testError()
	{
		$success = Registry::pm()->error('Error-Title', "The body of the error");

		$this->assertTrue($success);
	}

}
