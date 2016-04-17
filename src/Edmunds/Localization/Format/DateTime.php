<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Localization\Format;

use Edmunds\Http\Client\Visitor;
use IntlDateFormatter;
use DateTimeZone;

/**
 * The helper for localized DateTime
 */
class DateTime extends \Carbon\Carbon
{

	/**
	 * The locale
	 * @var string
	 */
	protected $locale;

	/**
	 * The output timezone
	 * @var DateTimeZone
	 */
	protected $outputTimezone;

	/**
	 * Constructor
	 * @param string|null $time
	 * @param string|null $outputTimezone
	 * @param string|null $locale
	 */
	public function __construct($time = null, $outputTimezone = null, $locale = null)
	{
		$localization = Visitor::getInstance()->localization;

		$outputTimezone = $outputTimezone ?: $localization->timezone;
		$this->outputTimezone = $this->safeCreateDateTimeZone($outputTimezone);

		$this->locale = $locale ?: $localization->locale;

		parent::__construct($time, config('edmunds.system.timezone'));
	}

	/**
	 * Format the instance as date
	 * @return string
	 */
	public function toDateString()
	{
		return $this->toFormattedString(IntlDateFormatter::SHORT, IntlDateFormatter::NONE, array($this, 'parent::toDateString'));
	}

	/**
	 * Format the instance as a readable date
	 * @return string
	 */
	public function toFormattedDateString()
	{
		return $this->toFormattedString(IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, array($this, 'parent::toFormattedDateString'));
	}

	/**
	 * Format the instance as time
	 * @return string
	 */
	public function toTimeString()
	{
		return $this->toFormattedString(IntlDateFormatter::NONE, IntlDateFormatter::MEDIUM, array($this, 'parent::toTimeString'));
	}

	/**
	 * Format the instance as date and time
	 * @return string
	 */
	public function toDateTimeString()
	{
		return $this->toFormattedString(IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM, array($this, 'parent::toDateTimeString'));
	}

	/**
	 * Format the instance with day, date and time
	 * @return string
	 */
	public function toDayDateTimeString()
	{
		return $this->toFormattedString(IntlDateFormatter::LONG, IntlDateFormatter::SHORT, array($this, 'parent::toDayDateTimeString'));
	}

	/**
	 * Format the datetime to a specific string
	 * @param  int $dateFormat
	 * @param  int $timeFormat
	 * @param  callable $backupCallable
	 * @return string
	 */
	protected function toFormattedString($dateFormat, $timeFormat, $backupCallable)
	{
		// set output timezone
		$backedupTimezone = $this->tz;
		$this->setTimezone($this->outputTimezone);

		// format date time
		$formatter = new IntlDateFormatter($this->locale, $dateFormat, $timeFormat, $this->tz);
		$string = (
			$formatter->format($this)
			?: call_user_func($backupCallable)
		);

		// set original timezone back
		$this->setTimezone($backedupTimezone);

		// return output
		return $string;
	}

}
