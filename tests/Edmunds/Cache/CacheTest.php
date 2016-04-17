<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace EdmundsTest\Registry\Cache;

use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Cache\Cache;

/**
 * Testing Cache-class
 */
class CacheTest extends BaseTest
{

	/**
	 * Test Constructor
	 */
	public function testConstructor()
	{
		$success = new Cache() != null;

		$this->assertTrue($success);
	}

	/**
	 * Test Actions
	 */
	public function testActions()
	{
		$cache = new Cache();
		$cacheKey = get_called_class() . '_test';

		// test save
		$cache->set($cacheKey, 'test');
		$this->assertTrue($cache->has($cacheKey));

		//test get
		$this->assertTrue($cache->get($cacheKey) == 'test');

		// test delete
		$this->assertTrue($cache->delete($cacheKey));
		$this->assertTrue($cache->has($cacheKey) == false);
	}
}
