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
use Edmunds\Http\Request;
use Edmunds\Auth\Models\User;
use Edmunds\Registry\Admin\Channel;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\City;

/**
 * The helper for the browser
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
	const	GEOIP_DIR	= 'app/geoip',
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
		return $this->belongsTo(config('app.auth.models.user'));
	}

	/**
	 * Initialize
	 * @param string $ip
	 */
	public function initialize($ip)
	{
		if (self::isEnabled())
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
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('user_id', 'ip'));

		$validator->rule('user_id')->integer();

		$validator->rule('ip')->ip()->max(255);

		$validator->rule('continent_code')->max(10);
		$validator->rule('continent_name')->max(255);

		$validator->rule('country_code')->max(10);
		$validator->rule('country_name')->max(255);

		$validator->rule('region_code')->max(10);
		$validator->rule('region_name')->max(255);

		$validator->rule('city_name')->max(255);
		$validator->rule('postal_code')->max(32);

		$validator->rule('latitude')->numeric();
		$validator->rule('longitude')->numeric();
		$validator->rule('timezone')->max(255);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'user_id' => $faker->numberBetween(),

			'ip' => $faker->ipv4,

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

	/**
	 * Check if location is enabled
	 * @return boolean
	 */
	public static function isEnabled()
	{
		return config('app.location.enabled', true);
	}
}
