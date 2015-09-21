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

namespace LH\Core\Helpers;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\City;
use GeoIp2\Model\Country;
use Illuminate\Support\Facades\App;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string continentCode
 * @property string continentName
 * @property string countryIso
 * @property string countryName
 * @property string cityName
 * @property string postalCode
 * @property float latitude
 * @property float longitude
 * @property \stdClass location
 */
class LocationHelper extends BaseHelper
{
	const	GEOIP_DIR	= 'geoip',
			GEOIP_CITY	= 'GeoLite2-City.mmdb',
			GEOIP_COUNTRY	= 'GeoLite2-Country.mmdb';

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
		if (RequestHelper::getInstance()->isLocalEnvironment())
		{
			$this->ip = '213.118.118.244';
		}
		else
		{
			$this->ip = $ip;
		}
	}

	/**
	 * Return the GeoIP of browser
	 * @return Reader
	 */
	private function getGeoIPCountry()
	{
		if (!isset($this->geoIP['country']))
		{
			$this->geoIP['country'] = new Reader(storage_path(self::GEOIP_DIR . '/' . self::GEOIP_COUNTRY));
		}
		return $this->geoIP['country'];
	}

	/**
	 * Return the details of country db
	 * @return Country
	 */
	private function getDetailsCountry()
	{
		if (!isset($this->details['country']))
		{
			try
			{
				$reader = $this->getGeoIPCountry();
				$this->details['country'] = $reader->country($this->ip);
			}
			catch(AddressNotFoundException $e)
			{
				$this->details['country'] = false;
			}
			catch(\InvalidArgumentException $e)
			{
				PmHelper::pmAdmin('GeoIP Error!', 'There is no country-geo-db.');
				$this->details['country'] = false;
			}
		}
		return $this->details['country'];
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
	 * @param $name
	 * @return mixed
	 */
	function __get($name)
	{
		$name = 'get' . ucfirst($name);
		return $this->$name();
	}

	/**
	 * Get the continentCode for the ip
	 * @return string
	 */
	private function getContinentCode()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->continent->code : null;
	}

	/**
	 * Get the continentName for the ip
	 * @return string
	 */
	private function getContinentName()
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
	private function getCountryIso()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->country->isoCode : null;
	}

	/**
	 * Get the countryName for the ip
	 * @return string
	 */
	private function getCountryName()
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
	 * Get the cityName for the ip
	 * @return string
	 */
	private function getCityName()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->city->name : null;
	}

	/**
	 * Get the postalCode for the ip
	 * @return string
	 */
	private function getPostalCode()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->postal->code : null;
	}

	/**
	 * Get the latitude for the ip
	 * @return float
	 */
	private function getLatitude()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->location->latitude : null;
	}

	/**
	 * Get the longitude for the ip
	 * @return float
	 */
	private function getLongitude()
	{
		return $this->getDetailsCity() ? $this->getDetailsCity()->location->longitude : null;
	}

	/**
	 * Get the location for the ip
	 * @return \stdClass
	 */
	private function getLocation()
	{
		list($latitude, $longitude) = array($this->getLatitude(), $this->getLongitude());

		$location = new \stdClass();
		$location->latitude = $latitude;
		$location->longitude = $longitude;

		return $location;
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
