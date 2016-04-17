<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Localization\Models;

use Edmunds\Bases\Models\BaseModel;
use Edmunds\Http\Client\Visitor;
use Edmunds\Localization\Models\Location;
use Edmunds\Auth\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NumberFormatter;
use Locale;

/**
 * The helper responsible for localization
 *
 * @property User $user
 * @property string $locale The default locale
 * @property-read string $rawLocale The raw default locale
 * @property-read string $fallback The fallback locale
 * @property-read bool $rtl Is language rtl
 * @property-read bool $fallbackRtl Is fallback rtl
 * @property string $currency The currency
 * @property string $timezone The timezone
 * @property string $measurement The measurement system
 * @readonl
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
		return $this->belongsTo(config('app.auth.models.user'));
	}

	/**
	 * Initialize the properties
	 * @param  string $timezone
	 * @param  string $countryCode
	 * @param  string $locale
	 * @param  string $fallback
	 */
	public function initialize($timezone, $countryCode, $locale, $fallback = null)
	{
		if (! self::isEnabled())
		{
			// not enabled, so set defaults
			$this->attributes['locale'] = $this->fallback;
			$this->attributes['currency'] = $this->getCurrencyFallback();
			$this->timezone = $this->getTimezoneFallback();
		}
		else
		{
			// check locale
			$locale = $locale ? self::normalizeLocale($locale) : null;
			$fallback = $fallback ? self::normalizeLocale($fallback) : null;

			if (($locale && $this->getAcceptedLocale($locale)) // given locale
				|| (($locale = $fallback) && $this->getAcceptedLocale($fallback)) // given fallback
				|| ($locale = $this->fallback))
			{
				$locale = Locale::parseLocale($locale);

				// add currency when possible
				if ($countryCode && (!isset($locale['region']) || !$locale['region']))
				{
					$locale['region'] = strtoupper($countryCode);
				}

				$this->attributes['locale'] = Locale::composeLocale($locale);
			}


			// check currency
			$formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
			$currency = self::normalizeCurrency($formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE));

			if ($currency && $this->getAcceptedCurrency($currency)
				|| ($currency = $this->getCurrencyFallback()))
			{
				$this->attributes['currency'] = $currency;
			}


			// check timezone
			if ($timezone
				|| ($timezone = $this->getTimezoneFallback()))
			{
				$this->timezone = $timezone;
			}


			// check measurement system
			if ($countryCode && ($measurement = in_array(strtoupper($countryCode), config('edmunds.localization.measurement.imperial.countries')) ? 'imperial' : 'metric')
				|| $measurement = $this->getMeasurementFallback())
			{
				$this->measurement = $measurement;
			}
		}
	}

	/**
	 * Get the default locale
	 * @return string
	 */
	protected function getLocaleAttribute()
	{
		return isset($this->attributes['locale']) ? $this->getAcceptedLocale($this->attributes['locale']) : null;
	}

	/**
	 * Set the locale
	 * @property string $locale
	 */
	protected function setLocaleAttribute($locale)
	{
		$locale = self::normalizeLocale($locale);

		if ($this->getAcceptedLocale($locale))
		{
			$this->attributes['locale'] = $locale;
		}
	}

	/**
	 * Get the raw locale
	 * @return string
	 */
	protected function getRawLocaleAttribute()
	{
		return $this->attributes['locale'] ?? null;
	}

	/**
	 * Get the fallback locale
	 * @return string
	 */
	protected function getFallbackAttribute()
	{
		return self::normalizeLocale(
			config('app.localization.locale.fallback')
			?: config('edmunds.localization.locale.fallback')
		);
	}

	/**
	 * Check if locale is right to left
	 * @return bool
	 */
	protected function getRtlAttribute()
	{
		return $this->isRtl($this->locale);
	}

	/**
	 * Check if fallback is right to left
	 * @return bool
	 */
	protected function getFallbackRtlAttribute()
	{
		return $this->isRtl($this->fallback);
	}

	/**
	 * Set the default currency
	 * @property string $currency
	 */
	protected function setCurrencyAttribute($currency)
	{
		$currency = self::normalizeCurrency($currency);

		if ($this->getAcceptedCurrency($currency))
		{
			$this->attributes['currency'] = $currency;
		}
	}

	/**
	 * Get the accepted locale from a locale
	 * @param  string $locale
	 * @return string
	 */
	protected function getAcceptedLocale($locale)
	{
		$acceptedLocales = config('app.localization.locale.accepted', array());

		if (in_array($locale, $acceptedLocales))
		{
			return $locale;
		}

		$locale = Locale::getPrimaryLanguage($locale);
		if (in_array($locale, $acceptedLocales))
		{
			return $locale;
		}

		return null;
	}

	/**
	 * Get the accepted currency
	 * @param  string $currency
	 * @return string
	 */
	protected function getAcceptedCurrency($currency)
	{
		if (in_array($currency, config('app.localization.currency.accepted', array())))
		{
			return $currency;
		}

		return null;
	}

	/**
	 * Check if locale is rtl
	 * @param  string  $locale
	 * @return bool
	 */
	protected function isRtl($locale)
	{
		return (
			config('edmunds.localization.locale.direction.languages.' . Locale::getPrimaryLanguage($locale)) == 'rtl'
			|| config('edmunds.localization.locale.direction.default') == 'rtl'
		);
	}

	/**
	 * Get the currency fallback
	 * @return string
	 */
	protected function getCurrencyFallback()
	{
		return self::normalizeCurrency(
			config('app.localization.currency.default')
			?: config('edmunds.localization.currency.default')
		);
	}

	/**
	 * Get the timezone fallback
	 * @return string
	 */
	protected function getTimezoneFallback()
	{
		return (
			config('app.localization.timezone.default')
			?: config('edmunds.localization.timezone.default')
		);
	}

	/**
	 * Get the measurement fallback
	 * @return string
	 */
	protected function getMeasurementFallback()
	{
		return (
			config('app.localization.measurement.default')
			?: config('edmunds.localization.measurement.default')
		);
	}

	/**
	 * Normalize a locale
	 * @param  string $locale
	 * @return string
	 */
	public static function normalizeLocale($locale)
	{
		return Locale::composeLocale(Locale::parseLocale($locale));
	}

	/**
	 * Normalize a currency
	 * @param  string $currency
	 * @return string
	 */
	public static function normalizeCurrency($currency)
	{
		return strtoupper($currency);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('user_id'));

		$validator->rule('user_id')->integer();
		$validator->rule('locale')->max(32);
		$validator->rule('currency')->max(10);
		$validator->rule('timezone')->max(255);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker@
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'user_id' => $faker->numberBetween(),
			'locale' => $faker->locale,
			'currency' => $faker->currencyCode,
			'timezone' => $faker->timezone,
		);
	}

	/**
	 * Check if localization is enabled
	 * @return boolean
	 */
	public static function isEnabled()
	{
		return config('app.localization.enabled', true);
	}
}
