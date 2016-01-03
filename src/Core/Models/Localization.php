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
use Core\Localization\DateTime;
use Core\Models\Location;
use Core\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NumberFormatter;

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
	 * The user
	 * @return BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Initialize the properties
	 * @param  string $locale
	 * @param  string $timezone
	 */
	public function initialize($locale, $timezone)
	{
		//Check locale
		if ($locale //try given locale
			|| ($locale = config('app.localization.locale.default')) //app default
			|| ($locale = config('core.localization.locale.default'))) //core default
		{
			// try glueing the countrycode to the back if no country is present
			if (strlen($locale) == 2
				&& ($countryCode = $visitor->location->country_code))
			{
				$locale .= "_$countryCode";
			}

			$this->locale = $locale;
		}

		//Check currency
		$formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

		if (($currency = $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE))
			|| ($currency = config('app.localization.currency.default')) //app default
			|| ($currency = config('core.localization.currency.default'))) //core default
		{
			$this->currency = $currency;
		}

		//Check timezone
		if ($timezone //fetch from location
			|| ($timezone = config('app.localization.timezone.default')) //app default
			|| ($timezone = config('core.localization.timezone.default'))) //core default
		{
			$this->timezone = $timezone;
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
		return $this->isRtl($this->language);
	}

	/**
	 * Get the fallback locale
	 * @return string
	 */
	protected function getFallbackAttribute()
	{
		return (
			config('app.localization.locale.fallback')
			?: config('core.localization.locale.fallback')
		);
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
		return $this->isRtl($this->fallbackLanguage);
	}

	/**
	 * Check if language is rtl
	 * @param  string  $language
	 * @return bool
	 */
	protected function isRtl($language)
	{
		return (
			config('core.localization.language.direction.languages.' . $language) == 'rtl'
			|| config('core.localization.language.direction.default') == 'rtl'
		);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->validator->value('user_id')->integer()->required();
		$this->validator->value('locale')->max(32);
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
			'locale' => $faker->locale,
			'currency' => $faker->currencyCode,
			'timezone' => $faker->timezone,
		);
	}

}