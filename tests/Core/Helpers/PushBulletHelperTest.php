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

namespace LH\CoreTest\Helpers;

use LH\Core\Helpers\PushBulletHelper;

/**
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @method void tearDown Tear down the test environment
 *
 */
class PushBulletHelperTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test Send note
	 */
	public function testSendNote()
	{
		PushBulletHelper::sendNote('Note-Title', 'Note-Message');
		$this->assertTrue(true);
	}

	/**
	 * Test Send link
	 */
	public function testSendLink()
	{
		PushBulletHelper::sendLink('Link-Title', 'http://lowiehuyghe.com', 'Link-Message');
		$this->assertTrue(true);
	}

	/**
	 * Test Send address
	 */
	public function testSendAddress()
	{
		PushBulletHelper::sendAddress('Address-Title', 'Kasteelstraat 75, 8792 Desselgem');
		$this->assertTrue(true);
	}

	/**
	 * Test Send list
	 */
	public function testSendList()
	{
		PushBulletHelper::sendList('List-Title', array('List', 'Off', 'Things'));
		$this->assertTrue(true);
	}

	/**
	 * Test Send file
	 */
	public function testSendFile()
	{
		PushBulletHelper::sendFile('File-Title', 'http://lowiehuyghe.com/assets/images/bg.jpg', 'File-Message');
		$this->assertTrue(true);
	}
}
