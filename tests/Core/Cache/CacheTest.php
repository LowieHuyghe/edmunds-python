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

namespace CoreTest\Registry\Cache;

use Core\Bases\Tests\BaseTest;
use Core\Cache\Cache;

/**
 * Testing Cache-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
