<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Localization\Format;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use NumberFormatter;

/**
 * The helper for localized Number
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Number extends BaseStructure
{

	/**
	 * Constructor
	 * @param mixed $number
	 * @param string|null $locale
	 * @param string|null $currency
	 */
	public function __construct($number, $locale = null, $currency = null)
	{
		$this->number = $number;

		$localization = Visitor::getInstance()->localization;
		$this->locale = $locale ?: $localization->locale;
		$this->currency = $currency ?: $localization->currency;
	}

	/**
	 * Format a number
	 * @return string
	 */
	public function format()
	{
		$formatter = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);
		$formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 0);
		$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 100);

		return $formatter->format($this->number);
	}

	/**
	 * Format a currency
	 * @return string
	 */
	public function formatCurrency()
	{
		$formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

		return $formatter->formatCurrency($this->number, $this->currency);
	}

	/**
	 * Format percentage
	 * @return string
	 */
	public function formatPercent()
	{
		$formatter = new NumberFormatter($this->locale, NumberFormatter::PERCENT);

		return $formatter->format($this->number);
	}

	/**
	 * Format scientific
	 * @return string
	 */
	public function formatScientific()
	{
		$formatter = new NumberFormatter($this->locale, NumberFormatter::SCIENTIFIC);

		return $formatter->format($this->number);
	}

	/**
	 * Format length
	 * @return string
	 */
	public function formatLength()
	{
		//
	}

	/**
	 * Format as liquid
	 * @return string
	 */
	public function formatLiquid()
	{
		//
	}
}
