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

namespace Core\Localization\Models;

use Core\Bases\Models\BaseModel;
use Core\Http\Request;
use Core\Auth\Models\User;
use Core\Registry\Admin\Pm;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\City;

/**
 * The helper for the browser
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property User $user
 * @property string $ip
 * @property string $continent_code
 * @property string $continent_name
 * @property string $country_code
 * @property string $country_name
 * @property string $region_code
 * @property string $region_name
 * @property string $city_name
 * @property string $postal_code
 * @property float $latitude
 * @property float $longitude
 * @property string $timezone
 */
class Location extends BaseModel
{
	const	GEOIP_DIR	= 'geoip',
			GEOIP_CITY	= 'GeoLite2-City.mmdb';

	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'user_locations';

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
	 * Initialize
	 * @param string $ip
	 */
	public function initialize($ip)
	{
		$cityDetails = $this->getDetailsCity($ip);

		if ($cityDetails)
		{
			$this->ip = $ip;

			$this->continent_code = $cityDetails->continent->code;
			$this->continent_name = $cityDetails->continent->name;

			$this->country_code = $cityDetails->country->isoCode;
			$this->country_name = $cityDetails->country->name;

			$this->region_code = $cityDetails->mostSpecificSubdivision->isoCode;
			$this->region_name = $cityDetails->mostSpecificSubdivision->name;

			$this->city_name = $cityDetails->city->name;
			$this->postal_code = $cityDetails->postal->code;

			$this->latitude = $cityDetails->location->latitude;
			$this->longitude = $cityDetails->location->longitude;
			$this->timezone = $cityDetails->location->timeZone;
		}
	}

	/**
	 * Return the GeoIP of browser
	 * @return Reader
	 */
	private function getGeoIPCity()
	{
		return new Reader(storage_path(self::GEOIP_DIR . '/' . self::GEOIP_CITY));
	}

	/**
	 * Return the details of city db
	 * @param string $ip
	 * @return City
	 */
	private function getDetailsCity($ip)
	{
		try
		{
			$reader = $this->getGeoIPCity();
			return $reader->city($ip);
		}
		catch(AddressNotFoundException $e)
		{
			return false;
		}
		catch(\InvalidArgumentException $e)
		{
			return false;
		}
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->required = array_merge($this->required, array('user_id', 'ip'));

		$this->validator->value('user_id')->integer();

		$this->validator->value('ip')->ip()->max(255);

		$this->validator->value('continent_code')->max(10);
		$this->validator->value('continent_name')->max(255);

		$this->validator->value('country_code')->max(10);
		$this->validator->value('country_name')->max(255);

		$this->validator->value('region_code')->max(10);
		$this->validator->value('region_name')->max(255);

		$this->validator->value('city_name')->max(255);
		$this->validator->value('postal_code')->max(32);

		$this->validator->value('latitude')->numeric();
		$this->validator->value('longitude')->numeric();
		$this->validator->value('timezone')->max(255);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'user_id' => $faker->integer,

			'ip' => $faker->ip,

			'continent_code' => $faker->countryCode,
			'continent_name' => $faker->country,

			'country_code' => $faker->countryCode,
			'country_name' => $faker->country,

			'region_code' => $faker->countryCode,
			'region_name' => $faker->country,

			'city_name' => $faker->city,
			'postal_code' => $faker->postcode,

			'latitude' => $faker->latitude,
			'longitude' => $faker->longitude,
			'timezone' => $faker->timezone,
		);
	}
}
