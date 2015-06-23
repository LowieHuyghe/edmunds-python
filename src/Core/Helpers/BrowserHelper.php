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
use Browser\Browser;
use Browser\Os;
use Browser\UserAgent;
use Illuminate\Support\Facades\Request;

/**
 * The helper to get device-details
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BrowserHelper extends BaseHelper
{
	/**
	 * Instance of UserAgent
	 * @var UserAgent
	 */
	private static $userAgentInstance;
	/**
	 * Instance of Browser
	 * @var Browser
	 */
	private static $browserInstance;
	/**
	 * Instance of Os
	 * @var Os
	 */
	private static $osInstance;

	/**
	 * Fetch instance of UserAgent
	 * @return UserAgent
	 */
	private static function getUserAgentInstance()
	{
		if (!isset(self::$userAgentInstance))
		{
			self::$userAgentInstance = new UserAgent(Request::server('HTTP_USER_AGENT'));
		}

		return self::$userAgentInstance;
	}
	/**
	 * Fetch instance of Browser
	 * @return Browser
	 */
	private static function getBrowserInstance()
	{
		if (!isset(self::$browserInstance))
		{
			self::$browserInstance = new Browser(self::getUserAgentInstance());
		}

		return self::$browserInstance;
	}
	/**
	 * Fetch instance of Os
	 * @return Os
	 */
	private static function getOsInstance()
	{
		if (!isset(self::$osInstance))
		{
			self::$osInstance = new Os(self::getUserAgentInstance());
		}

		return self::$osInstance;
	}

	/**
	 * Check if device is mobile
	 * @return bool
	 */
	public static function isMobile()
	{
		return self::getOsInstance()->isMobile();
	}

	/**
	 * Check if device is not mobile
	 * @return bool
	 */
	public static function isDesktop()
	{
		return !self::isMobile();
	}

	/**
	 * Check if it is Windows
	 * @return bool
	 */
	public static function isWindows()
	{
		return in_array(self::getOsInstance()->getName(), array(
			Os::WINDOWS,
		));
	}

	/**
	 * Check if it is Linux
	 * @return bool
	 */
	public static function isLinux()
	{
		return in_array(self::getOsInstance()->getName(), array(
			Os::LINUX,
		));
	}

	/**
	 * Check if it is iOS
	 * @return bool
	 */
	public static function isIOS()
	{
		return in_array(self::getOsInstance()->getName(), array(
			Os::IOS,
		));
	}

	/**
	 * Check if it is Android
	 * @return bool
	 */
	public static function isAndroid()
	{
		return in_array(self::getOsInstance()->getName(), array(
			Os::ANDROID,
		));
	}

	/**
	 * Check if it is a robot
	 * @return bool
	 */
	public static function isRobot()
	{
		return self::getBrowserInstance()->isRobot();
	}

	/**
	 * Check if it is Chrome
	 * @return bool
	 */
	public static function isChrome()
	{
		return in_array(self::getBrowserInstance()->getName(), array(
			Browser::CHROME,
		));
		return self::getBrowserInstance()->getIsChromeFrame();
	}

	/**
	 * Check if it is Firefox
	 * @return bool
	 */
	public static function isFirefox()
	{
		return in_array(self::getBrowserInstance()->getName(), array(
			Browser::FIREFOX,
		));
	}

	/**
	 * Check if it is Safari
	 * @return bool
	 */
	public static function isSafari()
	{
		return in_array(self::getBrowserInstance()->getName(), array(
			Browser::SAFARI,
		));
	}

	/**
	 * Check if it is IE
	 * @return bool
	 */
	public static function isIE()
	{
		return in_array(self::getBrowserInstance()->getName(), array(
			Browser::IE,
			Browser::POCKET_IE,
		));
	}

	/**
	 * Get the version of the browser
	 * @return string
	 */
	public static function getVersion()
	{
		return self::getBrowserInstance()->getVersion();
	}
}
