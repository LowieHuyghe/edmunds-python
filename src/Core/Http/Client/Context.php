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

namespace Core\Http\Client;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Request;

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
 * @property string $languageFallback
 * @property string $locale
 * @property string $localeFallback
 * @property string $acceptLanguage
 */
class Context extends BaseStructure
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
		parent::__construct();

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
		return $this->apple
			&& !$this->mobile
			&& !$this->tablet
			&& !$this->iOS;
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
	protected function getRobotAttribute()
	{
		return $this->getDetails()->isRobot();
	}

	/**
	 * Get the browser language
	 * @return string
	 */
	protected function getLanguageAttribute()
	{
		if ($locale = $this->locale)
		{
			return locale_get_primary_language($locale);
		}
		return null;
	}

	/**
	 * Get the browser language fallback
	 * @return string
	 */
	protected function getLanguageFallbackAttribute()
	{
		if ($locale = $this->localeFallback)
		{
			return locale_get_primary_language($locale);
		}
		return null;
	}

	/**
	 * Get the browser locale
	 * @return string
	 */
	protected function getLocaleAttribute()
	{
		return $this->getMostAcceptedLanguage();
	}

	/**
	 * Get the browser locale fallback
	 * @return string
	 */
	protected function getLocaleFallbackAttribute()
	{
		return $this->getMostAcceptedLanguage(1);
	}

	/**
	 * Get the most accepted language of the browser
	 * @param int $index
	 * @return string
	 */
	private function getMostAcceptedLanguage($index = 0)
	{
		if ($browserLanguage = $this->acceptLanguage)
		{
			// break up string into pieces (languages and q factors)
		    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $browserLanguage, $lang_parse);

		    if (count($lang_parse[1]))
		    {
		        // create a list like "en" => 0.8
		        $langs = array_combine($lang_parse[1], $lang_parse[4]);

		        // set default to 1 for any without q factor
		        foreach ($langs as $lang => $val) {
		            if ($val === '') $langs[$lang] = 1;
		        }

		        // sort list based on value
		        arsort($langs, SORT_NUMERIC);

				if (count($langs) > $index)
				{
			        return strtolower(array_keys($langs)[$index]);
				}
		    }
		}

		return null;
	}

	/**
	 * Get the http accept language
	 * @return string
	 */
	protected function getAcceptLanguage()
	{
		return Request::getInstance()->getServer('HTTP_ACCEPT_LANGUAGE');
	}
}
