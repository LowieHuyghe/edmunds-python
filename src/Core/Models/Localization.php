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

namespace Core\Models;
use Core\Bases\Models\BaseModel;
use Core\Http\Client\Context;
use Core\Http\Client\Location;
use Core\Http\Request;
use Core\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The helper responsible for localization
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property User $user
 * @property string $locale The default locale
 * @property string $language The default language
 * @property bool $rtl Is language rtl
 * @property string $fallback The fallback locale
 * @property string $fallbackLanguage The fallback language
 * @property bool $fallbackRtl Is fallback rtl
 * @property string $currency The currency
 * @property string $timezone The timezone
 * @property Location $location The location
 */
class Localization extends BaseModel
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'user_localizations';

	/**
	 * The primary key for the model.
	 * @var string
	 */
	protected $primaryKey = 'user_id';

	/**
	 * The key used to store the settings in the session and cookie
	 * @var string
	 */
	protected $sessionCookieKey = 'localization';

	/**
	 * Array that represents the attributes that are models
	 * @var array
	 */
	protected $models = []; //'location' => Location::class ];

	/**
	 * The user
	 * @return BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Initialize the properties
	 */
	public function initialize()
	{
		//Check location
		if (!$this->location)
		{
			$this->location = new Location();
		}

		//Check locale
		if (!$this->locale)
		{
			$request = Request::getInstance();
			$context = new Context($request->userAgent);

			if (($locale = $context->locale) //try context
				|| false && ($locale = $this->location->locale) //try location
				|| ($locale = config('app.locale'))) //app default
			{
				$this->locale = $locale;
			}
		}

		//Check currency
		if (!$this->currency)
		{
			if (($currency = config('core.localization.currency.countries.' . strtoupper($this->location->countryCode)))
				|| ($currency = config('app.currency'))) //app default
			{
				$this->currency = $currency;
			}
		}

		//Check timezone
		if (!$this->timezone)
		{
			if ($this->location->location && ($timezone = $this->location->location->timeZone) //fetch from location
				|| ($timezone = config('app.timezone'))) //app default
			{
				$this->timezone = $timezone;
			}
		}
	}

	/**
	 * Get the default language
	 * @return string
	 */
	protected function getLanguageAttribute()
	{
		return locale_get_primary_language($this->locale);
	}

	/**
	 * Check if language is right to left
	 * @return bool
	 */
	protected function getRtlAttribute()
	{
		return in_array($this->language, config('core.localization.language.rtl'));
	}

	/**
	 * Get the fallback locale
	 * @return string
	 */
	protected function getFallbackAttribute()
	{
		return config('app.fallback');
	}

	/**
	 * Get the fallback language
	 * @return string
	 */
	protected function getFallbackLanguageAttribute()
	{
		return locale_get_primary_language($this->fallback);
	}

	/**
	 * Check if fallback is right to left
	 * @return bool
	 */
	protected function getFallbackRtlAttribute()
	{
		return in_array($this->fallbackLanguage, config('core.localization.language.rtl'));
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->validator->value('user_id')->integer()->required();
		$this->validator->value('locale')->max(10);
		$this->validator->value('currency')->max(10);
		$this->validator->value('timezone')->max(255);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker@
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'user_id' => $faker->integer,
			'locale' => str_random(2),
			'currency' => str_random(10),
			'timezone' => str_random(32),
		);
	}

}