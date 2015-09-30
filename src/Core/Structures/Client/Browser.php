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

namespace LH\Core\Structures\Client;
use LH\Core\Structures\BaseStructure;
use LH\Core\Structures\Http\Request;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $userAgent
 * @property bool $chrome
 * @property bool $firefox
 * @property bool $safari
 * @property bool $IE
 * @property bool $opera
 * @property bool $windows
 * @property bool $apple
 * @property bool $mac
 * @property bool $linux
 * @property bool $android
 * @property bool $iOS
 * @property bool $nokia
 * @property bool $blackberry
 * @property bool $mobile
 * @property bool $tablet
 * @property bool $robot
 * @property string $language
 * @property string $locale
 */
class Browser extends BaseStructure
{
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
	protected function getChromeAttribute()
	{
		return $this->isBrowser(\Browser::BROWSER_CHROME);
	}

	/**
	 * Check if Firefox
	 * @return bool
	 */
	protected function getFirefoxAttribute()
	{
		return $this->isBrowser(\Browser::BROWSER_FIREFOX);
	}

	/**
	 * Check if Safari
	 * @return bool
	 */
	protected function getSafariAttribute()
	{
		return $this->isBrowser(\Browser::BROWSER_SAFARI);
	}

	/**
	 * Check if IE
	 * @return bool
	 */
	protected function getIEAttribute()
	{
		return $this->isBrowser(\Browser::BROWSER_IE);
	}

	/**
	 * Check if Opera
	 * @return bool
	 */
	protected function getOperaAttribute()
	{
		return $this->isBrowser(array(\Browser::BROWSER_OPERA, \Browser::BROWSER_OPERA_MINI));
	}

	/**
	 * Check if Windows
	 * @return bool
	 */
	protected function getWindowsAttribute()
	{
		return $this->isPlatform(array(\Browser::PLATFORM_WINDOWS, \Browser::PLATFORM_WINDOWS_CE));
	}

	/**
	 * Check if Apple
	 * @return bool
	 */
	protected function getAppleAttribute()
	{
		return $this->isPlatform(\Browser::PLATFORM_APPLE);
	}

	/**
	 * Check if Mac
	 * @return bool
	 */
	protected function getMacAttribute()
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
	protected function getLinuxAttribute()
	{
		return $this->isPlatform(\Browser::PLATFORM_LINUX);
	}

	/**
	 * Check if Android
	 * @return bool
	 */
	protected function getAndroidAttribute()
	{
		return $this->isPlatform(\Browser::PLATFORM_ANDROID)
			|| $this->isBrowser(\Browser::BROWSER_ANDROID);
	}

	/**
	 * Check if iOS
	 * @return bool
	 */
	protected function getIOSAttribute()
	{
		return $this->isPlatform(array(\Browser::PLATFORM_IPHONE, \Browser::PLATFORM_IPAD, \Browser::PLATFORM_IPOD))
		|| $this->isBrowser(array(\Browser::BROWSER_IPHONE, \Browser::BROWSER_IPAD, \Browser::BROWSER_IPOD));
	}

	/**
	 * Check if Nokia
	 * @return bool
	 */
	protected function getNokiaAttribute()
	{
		return $this->isPlatform(\Browser::PLATFORM_NOKIA)
		|| $this->isBrowser(array(\Browser::BROWSER_NOKIA, \Browser::BROWSER_NOKIA_S60));
	}

	/**
	 * Check if BlackBerry
	 * @return bool
	 */
	protected function getBlackBerryAttribute()
	{
		return $this->isPlatform(\Browser::PLATFORM_BLACKBERRY)
		|| $this->isBrowser(\Browser::BROWSER_BLACKBERRY);
	}

	/**
	 * Check if mobile
	 * @return bool
	 */
	protected function getMobileAttribute()
	{
		return $this->getDetails()->isMobile();
	}

	/**
	 * Check if mobile
	 * @return bool
	 */
	protected function getTabletAttribute()
	{
		return $this->getDetails()->isTablet();
	}

	/**
	 * Check if robot
	 * @return bool
	 */
	protected function getRobotAttributeAttribute()
	{
		return $this->getDetails()->isRobot();
	}

	/**
	 * Get the browser language
	 * @return string
	 */
	protected function getLanguageAttributeAttribute()
	{
		if ($browserLanguage = Request::getInstance()->getServer('HTTP_ACCEPT_LANGUAGE'))
		{
			$parts = explode(',',$browserLanguage);
			return $parts[0];
		}
		return null;
	}

	/**
	 * Get the browser locale
	 * @return string
	 */
	protected function getLocaleAttribute()
	{
		if ($browserLanguage = Request::getInstance()->getServer('HTTP_ACCEPT_LANGUAGE'))
		{
			$parts = explode(',',$browserLanguage);
			return $parts[1];
		}
		return null;
	}
}
