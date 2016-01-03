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
     * Create a new Carbon instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string|null              $time
     * @param DateTimeZone|string|null $tz
     */
    public function __construct($time = null, $tz = null)
    {
        $localization = Visitor::getInstance()->localization;

    	if (is_null($tz))
    	{
    		$tz = $localization->timezone;
    	}
        $this->locale = $localization->locale;

    	parent::__construct($time, $tz);
    }

    /**
     * Format the instance as date
     * @return string
     */
    public function toDateString()
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::SHORT, IntlDateFormatter::NONE, $this->tz);
        return (
            $formatter->format($this)
            ?: parent::toDateString()
        );
    }

    /**
     * Format the instance as a readable date
     * @return string
     */
    public function toFormattedDateString()
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, $this->tz);
        return (
            $formatter->format($this)
            ?: parent::toFormattedDateString()
        );
    }

    /**
     * Format the instance as time
     * @return string
     */
    public function toTimeString()
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::NONE, IntlDateFormatter::MEDIUM, $this->tz);
        return (
            $formatter->format($this)
            ?: parent::toTimeString()
        );
    }

    /**
     * Format the instance as date and time
     * @return string
     */
    public function toDateTimeString()
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM, $this->tz);
        return (
            $formatter->format($this)
            ?: parent::toDateTimeString()
        );
    }

    /**
     * Format the instance with day, date and time
     * @return string
     */
    public function toDayDateTimeString()
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::LONG, IntlDateFormatter::SHORT, $this->tz);
        return (
            $formatter->format($this)
            ?: parent::toDayDateTimeString()
        );
    }

}
