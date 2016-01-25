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
use Core\Registry;

/**
 * Testing Registry-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
	 * Test Pm default
	 */
	public function testPm()
	{
		$success = Registry::pm() != null;

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
