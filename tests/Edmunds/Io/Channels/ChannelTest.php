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

namespace EdmundsTest\Io\Channels;

use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Registry;

/**
 * Testing Channel-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */
class ChannelTest extends BaseTest
{

	/**
	 * Test Info
	 */
	public function testInfo()
	{
		$success = Registry::channel()->info('Info-Title', "The body of the info");

		$this->assertTrue($success);
	}

	/**
	 * Test Warning
	 */
	public function testWarning()
	{
		$success = Registry::channel()->warning('Warning-Title', "The body of the warning");

		$this->assertTrue($success);
	}

	/**
	 * Test Error
	 */
	public function testError()
	{
		$success = Registry::channel()->error('Error-Title', "The body of the error");

		$this->assertTrue($success);
	}

}
