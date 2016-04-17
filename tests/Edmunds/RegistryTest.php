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
use Edmunds\Registry;

/**
 * Testing Registry-class
 */
class RegistryTest extends BaseTest
{

	/**
	 * Test Db default
	 */
	public function testDb()
	{
		$success = Registry::db() != null;

		$this->assertTrue($success);
	}

	/**
	 * Test Cache default
	 */
	public function testCache()
	{
		$success = Registry::cache() != null;

		$this->assertTrue($success);
	}

	/**
	 * Test Queue default
	 */
	public function testQueue()
	{
		$success = Registry::queue() != null;

		$this->assertTrue($success);
	}

	/**
	 * Test Channel default
	 */
	public function testChannel()
	{
		$success = Registry::channel() != null;

		$this->assertTrue($success);
	}

	/**
	 * Test Warehouse default
	 */
	public function testWarehouse()
	{
		$success = Registry::warehouse() != null;

		$this->assertTrue($success);
	}

}
