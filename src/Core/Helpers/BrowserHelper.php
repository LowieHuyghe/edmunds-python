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

namespace LH\Core\Helpers;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BrowserHelper extends BaseHelper
{
	/**
	 * @var string
	 */
	private $userAgent;

	/**
	 * @var \Browser
	 */
	private $details;

	/**
	 * Constructor
	 * @param string $userAgent
	 */
	public function __construct($userAgent)
	{
		$this->userAgent = $userAgent;
	}

	/**
	 * Return the browser details
	 * @return \Browser
	 */
	private function getDetails()
	{
		if (!isset($this->details))
		{
			$this->details = new \Browser($this->userAgent);
		}
		return $this->details;
	}

	/**
	 * Check if it is browser
	 * @param string|array $browsers
	 * @return bool
	 */
	public function isBrowser($browsers)
	{
		//Make array of string
		if (!is_array($browsers))
		{
			$browsers = array($browsers);
		}

		//Fetch current browser
		$details = $this->getDetails();

		//When match, return true
		foreach ($browsers as $browser)
		{
			if ($details->isBrowser($browser))
			{
				return true;
			}
		}

		//No match
		return false;
	}

	/**
	 * Check if it is platform
	 * @param string|array $platforms
	 * @return bool
	 */
	public function isPlatform($platforms)
	{
		//Make array of string
		if (!is_array($platforms))
		{
			$platforms = array($platforms);
		}

		//Fetch current browser
		$currentPlatform = $this->getDetails()->getPlatform();

		//When match, return true
		foreach ($platforms as $platform)
		{
			if (0 == strcasecmp($currentPlatform, trim($platform)))
			{
				return true;
			}
		}

		//No match
		return false;
	}

	/**
	 * Check if Chrome
	 * @return bool
	 */
	public function isChrome()
	{
		return $this->isBrowser(\Browser::BROWSER_CHROME);
	}

	/**
	 * Check if Firefox
	 * @return bool
	 */
	public function isFirefox()
	{
		return $this->isBrowser(\Browser::BROWSER_FIREFOX);
	}

	/**
	 * Check if Safari
	 * @return bool
	 */
	public function isSafari()
	{
		return $this->isBrowser(\Browser::BROWSER_SAFARI);
	}

	/**
	 * Check if IE
	 * @return bool
	 */
	public function isIE()
	{
		return $this->isBrowser(\Browser::BROWSER_IE);
	}

	/**
	 * Check if Opera
	 * @return bool
	 */
	public function isOpera()
	{
		return $this->isBrowser(array(\Browser::BROWSER_OPERA, \Browser::BROWSER_OPERA_MINI));
	}

	/**
	 * Check if Windows
	 * @return bool
	 */
	public function isWindows()
	{
		return $this->isPlatform(array(\Browser::PLATFORM_WINDOWS, \Browser::PLATFORM_WINDOWS_CE));
	}

	/**
	 * Check if Apple
	 * @return bool
	 */
	public function isApple()
	{
		return $this->isPlatform(\Browser::PLATFORM_APPLE);
	}

	/**
	 * Check if Mac
	 * @return bool
	 */
	public function isMac()
	{
		return $this->isApple()
			&& !$this->isMobile()
			&& !$this->isTablet()
			&& !$this->isIOS();
	}

	/**
	 * Check if Linux
	 * @return bool
	 */
	public function isLinux()
	{
		return $this->isPlatform(\Browser::PLATFORM_LINUX);
	}

	/**
	 * Check if Android
	 * @return bool
	 */
	public function isAndroid()
	{
		return $this->isPlatform(\Browser::PLATFORM_ANDROID)
			|| $this->isBrowser(\Browser::BROWSER_ANDROID);
	}

	/**
	 * Check if iOS
	 * @return bool
	 */
	public function isIOS()
	{
		return $this->isPlatform(array(\Browser::PLATFORM_IPHONE, \Browser::PLATFORM_IPAD, \Browser::PLATFORM_IPOD))
		|| $this->isBrowser(array(\Browser::BROWSER_IPHONE, \Browser::BROWSER_IPAD, \Browser::BROWSER_IPOD));
	}

	/**
	 * Check if Nokia
	 * @return bool
	 */
	public function isNokia()
	{
		return $this->isPlatform(\Browser::PLATFORM_NOKIA)
		|| $this->isBrowser(array(\Browser::BROWSER_NOKIA, \Browser::BROWSER_NOKIA_S60));
	}

	/**
	 * Check if BlackBerry
	 * @return bool
	 */
	public function isBlackBerry()
	{
		return $this->isPlatform(\Browser::PLATFORM_BLACKBERRY)
		|| $this->isBrowser(\Browser::BROWSER_BLACKBERRY);
	}

	/**
	 * Check if mobile
	 * @return bool
	 */
	public function isMobile()
	{
		return $this->getDetails()->isMobile();
	}

	/**
	 * Check if mobile
	 * @return bool
	 */
	public function isTablet()
	{
		return $this->getDetails()->isTablet();
	}

	/**
	 * Check if robot
	 * @return bool
	 */
	public function isRobot()
	{
		return $this->getDetails()->isRobot();
	}
}
