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
use Core\Models\Localization;
use DeviceDetector\DeviceDetector;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * // Type
 * @property boolean $typeDesktop
 * @property boolean $typeMobile
 * @property boolean $typeBot
 * // OS
 * @property boolean $osOSX
 * @property boolean $osWindows
 * @property boolean $osLinux
 * @property boolean $osAndroid
 * @property boolean $osIOS
 * @property string $osVersion
 * // Browser
 * @property boolean $browserChrome
 * @property boolean $browserFirefox
 * @property boolean $browserIE
 * @property boolean $browserSafari
 * @property boolean $browserOpera
 * @property string $browserVersion
 * // Localization
 * @property string $locale
 * @property string $localeFallback
 * @property string $acceptLanguage
 */
class Context extends BaseStructure
{
	/**
	 * @var DeviceDetector
	 */
	protected $detector;

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
	 * Return the browser detector
	 * @return DeviceDetector
	 */
	protected function getDetector()
	{
		if (!isset($this->detector))
		{
			$this->detector = new DeviceDetector($this->userAgent);
			$this->detector->parse();
		}
		return $this->detector;
	}

	protected function getTypeDesktopAttribute()
	{
		return $this->getDetector()->isDesktop();
	}
	protected function getTypeMobileAttribute()
	{
		return $this->getDetector()->isMobile();
	}
	protected function getTypeBotAttribute()
	{
		return $this->getDetector()->isBot();
	}
	protected function getOsOSXAttribute()
	{
		return $this->getDetector()->getOs('name') == 'Mac';
	}
	protected function getOsWindowsAttribute()
	{
		return $this->getDetector()->getOs('name') == 'Windows';
	}
	protected function getOsLinuxAttribute()
	{
		return $this->getDetector()->getOs('name') == 'GNU/Linux';
	}
	protected function getOsAndroidAttribute()
	{
		return $this->getDetector()->getOs('name') == 'Android';
	}
	protected function getOsIOSAttribute()
	{
		return $this->getDetector()->getOs('name') == 'iOS';
	}
	protected function getOsVersionAttribute()
	{
		return $this->getDetector()->getOs('version');
	}
	protected function getBrowserChromeAttribute()
	{
		return $this->getDetector()->getClient('name') == 'Chrome';
	}
	protected function getBrowserFirefoxAttribute()
	{
		return $this->getDetector()->getClient('name') == 'Firefox';
	}
	protected function getBrowserIEAttribute()
	{
		return $this->getDetector()->getClient('name') == 'Internet Explorer';
	}
	protected function getBrowserSafariAttribute()
	{
		return $this->getDetector()->getClient('name') == 'Safari';
	}
	protected function getBrowserOperaAttribute()
	{
		return $this->getDetector()->getClient('name') == 'Opera';
	}
	protected function getBrowserVersionAttribute()
	{
		return $this->getDetector()->getClient('version');
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
	protected function getMostAcceptedLanguage($index = 0)
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
			        return Localization::normalizeLocale(array_keys($langs)[$index]);
				}
		    }
		}

		return null;
	}

	/**
	 * Get the http accept language
	 * @return string
	 */
	protected function getAcceptLanguageAttribute()
	{
		return Request::getInstance()->getServer('HTTP_ACCEPT_LANGUAGE');
	}
}
