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
use Illuminate\Support\Facades\Config;
use LH\Core\Helpers\BaseHelper;
use LH\Core\Models\User;
use LH\Core\Structures\Client\Location;

/**
 * The helper responsible for localization
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Localization extends BaseHelper
{
	/**
	 * The default locale
	 * @var string
	 */
	public $locale;

	/**
	 * The fallback locale
	 * @var string
	 */
	public $fallback;

	/**
	 * Constructor
	 * @param BrowserHelper $browser
	 * @param Location $location
	 * @param User $user
	 */
	public function __construct($browser, $location, $user)
	{
		$locale = null;
		$fallback = null;

		//Use user for locale
		if ($user)
		{
			$locale = $user->locale;
		}

		//Use location for locale
		if ($location)
		{
			//
		}

		//Use browser for locale
		if ($browser)
		{
			if (!$locale)
			{
				$locale = $browser->getLanguage(true);
			}
			elseif (!$fallback)
			{
				$browserLocale = $browser->getLanguage(true);
				if ($browserLocale != $locale)
				{
					$fallback = $browserLocale;
				}
			}
		}

		//Use app-settings
		if (!$locale)
		{
			$locale = Config::get('app.locale');
			$fallback = Config::get('app.fallback_locale');
		}
		else if (!$fallback)
		{
			$appLocale = Config::get('app.locale');
			if ($appLocale != $locale)
			{
				$fallback = $appLocale;
			}
			else
			{
				$fallback = Config::get('app.fallback_locale');
			}
		}

		//Set
		$this->locale = $locale;
		$this->fallback = $fallback;
	}

}
