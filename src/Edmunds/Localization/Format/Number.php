<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Localization\Format;

use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Http\Client\Visitor;
use NumberFormatter;

/**
 * The helper for localized Number
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
