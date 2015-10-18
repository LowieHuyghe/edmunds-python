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

namespace Core\Structures\Client;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\City;
use Core\Structures\BaseStructure;
use Core\Structures\Http\Request;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $continentCode
 * @property string $continentName
 * @property string $countryCode
 * @property string $countryName
 * @property string $regionCode
 * @property string $regionName
 * @property string $cityName
 * @property string $postalCode
 * @property float $latitude
 * @property float $longitude
 * @property \GeoIp2\Record\Location $location
 */
class Location extends BaseStructure
{
	const	GEOIP_DIR	= 'geoip',
			GEOIP_CITY	= 'GeoLite2-City.mmdb';

	/**
	 * @var string
	 */
	private $ip;

	/**
	 * @var Reader[]
	 */
	private $geoIP = array();

	/**
	 * @var \stdClass[]
	 */
	private $details = array();

	/**
	 * Constructor
	 * @param string $ip
	 */
	public function __construct($ip)
	{
		parent::__construct();

		$this->ip = $ip;
	}

	/**
	 * Return the GeoIP of browser
	 * @return Reader
	 */
	private function getGeoIPCity()
	{
		if (!isset($this->geoIP['city']))
		{
			$this->geoIP['city'] = new Reader(storage_path(self::GEOIP_DIR . '/' . self::GEOIP_CITY));
		}
		return $this->geoIP['city'];
	}

	/**
	 * Return the details of city db
	 * @return City
	 */
	private function getDetailsCity()
	{
		if (!isset($this->details['city']))
		{
			try
			{
				$reader = $this->getGeoIPCity();
				$this->details['city'] = $reader->city($this->ip);
			}
			catch(AddressNotFoundException $e)
			{
				$this->details['city'] = false;
			}
			catch(\InvalidArgumentException $e)
			{
				PmHelper::pmAdmin('GeoIP Error!', 'There is no city-geo-db.');
				$this->details['city'] = false;
			}
		}
		return $this->details['city'];
	}

	/**
	 * Get the continentCode for the ip
	 * @return string
	 */
	protected function getContinentCodeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->continent->code : null;
	}

	/**
	 * Get the continentName for the ip
	 * @return string
	 */
	protected function getContinentNameAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->continent->name : null;
	}

	/**
	 * Get the continentName for the ip by locale
	 * @param string locale
	 * @param bool $fallback
	 * @return string
	 */
	public function getContinentNameByLocale($locale, $fallback = true)
	{
		if ($this->getDetailsCity())
		{
			//Try to fetch the name of the continent in the right language
			$names = $this->getDetailsCity()->continent->names;
			if (isset($names[$locale]))
			{
				return $names[$locale];
			}

			if ($fallback)
			{
				return $this->getContinentName();
			}
		}

		return null;
	}

	/**
	 * Get the countryIso for the ip
	 * @return string
	 */
	protected function getCountryCodeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->country->isoCode : null;
	}

	/**
	 * Get the countryName for the ip
	 * @return string
	 */
	protected function getCountryNameAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->country->name : null;
	}

	/**
	 * Get the countryName for the ip by locale
	 * @param string locale
	 * @param bool $fallback
	 * @return string
	 */
	public function getCountryNameByLocale($locale, $fallback = true)
	{
		if ($this->getDetailsCity())
		{
			//Try to fetch the name of the country in the right language
			$names = $this->getDetailsCity()->country->names;
			if (isset($names[$locale])) {
				return $names[$locale];
			}

			if ($fallback) {
				return $this->getCountryName();
			}
		}

		return null;
	}

	/**
	 * Get the regionIso for the ip
	 * @return string
	 */
	protected function getRegionCodeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->mostSpecificSubdivision->isoCode : null;
	}

	/**
	 * Get the regionName for the ip
	 * @return string
	 */
	protected function getRegionNameAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->mostSpecificSubdivision->name : null;
	}

	/**
	 * Get the regionName for the ip by locale
	 * @param string locale
	 * @param bool $fallback
	 * @return string
	 */
	public function getRegionNameByLocale($locale, $fallback = true)
	{
		if ($this->getDetailsCity())
		{
			//Try to fetch the name of the region in the right language
			$names = $this->getDetailsCity()->mostSpecificSubdivision->names;
			if (isset($names[$locale])) {
				return $names[$locale];
			}

			if ($fallback) {
				return $this->getRegionName();
			}
		}

		return null;
	}

	/**
	 * Get the cityName for the ip
	 * @return string
	 */
	protected function getCityNameAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->city->name : null;
	}

	/**
	 * Get the postalCode for the ip
	 * @return string
	 */
	protected function getPostalCodeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->postal->code : null;
	}

	/**
	 * Get the latitude for the ip
	 * @return float
	 */
	protected function getLatitudeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->location->latitude : null;
	}

	/**
	 * Get the longitude for the ip
	 * @return float
	 */
	protected function getLongitudeAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->location->longitude : null;
	}

	/**
	 * Get the location for the ip
	 * @return \GeoIp2\Record\Location
	 */
	protected function getLocationAttribute()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->location : null;
	}

	/**
	 * Return the raw details of the location
	 * @return array
	 */
	public function getRaw()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->raw : null;
	}
}
