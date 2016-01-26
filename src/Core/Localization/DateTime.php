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

namespace Core\Localization;

use Core\Http\Client\Visitor;
use IntlDateFormatter;
use DateTimeZone;

/**
 * The helper for localized DateTime
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
     */
    public function __construct($time = null, $outputTimezone = null)
    {
        $localization = Visitor::getInstance()->localization;

    	if (is_null($outputTimezone))
    	{
    		$outputTimezone = $localization->timezone;
    	}
        $this->outputTimezone = $this->safeCreateDateTimeZone($outputTimezone);
        $this->locale = $localization->locale;

    	parent::__construct($time, config('core.system.timezone'));
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
