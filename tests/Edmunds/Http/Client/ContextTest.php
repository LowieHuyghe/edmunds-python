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

namespace EdmundsTest;

use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Http\Client\Context;

/**
 * Testing Context-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class ContextTest extends BaseTest
{
	protected $userAgents = array(
		'iphone' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4',
		'nexus' => 'Mozilla/5.0 (Linux; Android 4.4.4; Nexus 5 Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.114 Mobile Safari/537.36',
		'lumia' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 520)',
		'osx_safari' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A',
		'linux_firefox' => 'Mozilla/5.0 (X11; U; Linux x86_64; de; rv:1.9.2.8) Gecko/20100723 Ubuntu/10.04 (lucid) Firefox/3.6.8',
		'windows_chrome' => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1',
		'windows_opera' => 'Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.0) Opera 7.02 Bork-edition [en]',
		'bot' => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
	);

	/**
	 * Test Iphone
	 */
	public function testIPhone()
	{
		$context = new Context($this->userAgents['iphone']);

		$this->assertTrue($context->typeMobile);
		$this->assertTrue($context->osIOS);
		$this->assertTrue($context->browserSafari);
	}

	/**
	 * Test Nexus
	 */
	public function testNexus()
	{
		$context = new Context($this->userAgents['nexus']);

		$this->assertTrue($context->typeMobile);
		$this->assertTrue($context->osAndroid);
		$this->assertTrue($context->browserChrome);
	}

	/**
	 * Test Lumia
	 */
	public function testLumia()
	{
		$context = new Context($this->userAgents['lumia']);

		$this->assertTrue($context->typeMobile);
		$this->assertTrue($context->osWindowsMobile);
		$this->assertTrue($context->browserIE);
	}

	/**
	 * Test OSX Safari
	 */
	public function testOSXSafari()
	{
		$context = new Context($this->userAgents['osx_safari']);

		$this->assertTrue($context->typeDesktop);
		$this->assertTrue($context->osOSX);
		$this->assertTrue($context->browserSafari);
	}

	/**
	 * Test Linux Firefox
	 */
	public function testLinuxFirefox()
	{
		$context = new Context($this->userAgents['linux_firefox']);

		$this->assertTrue($context->typeDesktop);
		$this->assertTrue($context->osLinux);
		$this->assertTrue($context->browserFirefox);
	}

	/**
	 * Test Windows Chrome
	 */
	public function testWindowsChrome()
	{
		$context = new Context($this->userAgents['windows_chrome']);

		$this->assertTrue($context->typeDesktop);
		$this->assertTrue($context->osWindows);
		$this->assertTrue($context->browserChrome);
	}

	/**
	 * Test Windows Opera
	 */
	public function testWindowsOpera()
	{
		$context = new Context($this->userAgents['windows_opera']);

		$this->assertTrue($context->typeDesktop);
		$this->assertTrue($context->osWindows);
		$this->assertTrue($context->browserOpera);
	}

	/**
	 * Test Bot
	 */
	public function testBot()
	{
		$context = new Context($this->userAgents['bot']);

		$this->assertTrue($context->typeBot);
	}
}
