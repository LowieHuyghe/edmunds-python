<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace EdmundsTest\Io\Channels;

use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Registry;

/**
 * Testing Channel-class
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
