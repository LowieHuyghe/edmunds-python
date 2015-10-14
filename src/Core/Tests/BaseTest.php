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

namespace Core\Tests;

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
	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		if (!defined('BASE_PATH'))
		{
			define('BASE_PATH', __DIR__ . '/../../..');
		}
		return require __DIR__.'/../../bootstrap/core.php';
	}
}
