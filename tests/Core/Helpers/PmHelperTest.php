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

namespace CoreTest\Helpers;

use Core\Structures\Registry\Admin\Pm;
use Core\Structures\Registry\Registry;
use Core\Tests\BaseTest;

/**
 * Testing Pm-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PmHelperTest extends BaseTest
{

	/**
	 * Test Send note
	 */
	public function testSendNote()
	{
		$success = Registry::adminPm()->sendNote('Note-Title', "Note-Body\nhttp://www.pinterest.com");

		$this->assertTrue($success);
	}

	/**
	 * Test Send file
	 */
	public function testSendFile()
	{
		$success = Registry::adminPm()->sendFile('File-Title', "https://www.google.be/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png", 'File-Body');

		$this->assertTrue($success);
	}

}
