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

namespace Core\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Core\Structures\Validation;

/**
 * The model of the enum-models
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property string $name Name of the value
 */
class BaseEnumModel extends BaseModel
{
	/**
	 * The fetched constants
	 * @var array
	 */
	private static $constants = array();

	/**
	 * Fetch all the defined constants
	 * @return array
	 */
	private static function getConstants()
	{
		$calledClass = get_called_class();

		//Check if already fetched
		if (!isset(self::$constants[$calledClass]))
		{
			//Get all the constants
			$reflect = new \ReflectionClass($calledClass);
			$constants = array_change_key_case($reflect->getConstants());
			//Filter some out
			foreach ($constants as $name => $value)
			{
				if (ends_with($name, array('_at')))
				{
					unset($constants[$name]);
				}
			}
			self::$constants[$calledClass] = $constants;
		}
		return self::$constants[$calledClass];
	}

	/**
	 * Get all of the models
	 * @param  array  $columns
	 * @return Collection
	 */
	public static function all($columns = [])
	{
		//Fetch required columns
		$columns = is_array($columns) ? $columns : func_get_args();

		//Process all constants
		$all = array();
		$constants = self::getConstants();
		$calledClass = get_called_class();
		foreach ($constants as $name => $value)
		{
			$object = new $calledClass();
			if (empty($columns) || in_array('id', $columns))
			{
				$object->id = $value;
			}
			if (empty($columns) || in_array('name', $columns))
			{
				$object->name = $name;
			}

			$all[] = $object;
		}

		return collect($all);
	}

	/**
	 * Find a model by its primary key or return new static.
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return BaseEnumModel
	 */
	public static function find($id, $columns = [])
	{
		//Fetch all and search for right id
		$all = self::all($columns);
		foreach ($all->all() as $enum)
		{
			if ($enum->id == $id)
			{
				return $enum;
			}
		}

		return null;
	}

	/**
	 * Get object with where
	 * @param $name
	 * @param $value
	 * @return static
	 */
	public static function where($name, $value)
	{
		$all = self::all();
		return $all->where($name, $value);
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->value('id')->integer();
		$validator->value('name')->required()->max(32);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'id' => rand(1,99),
			'name' => str_random(10),
		);
	}

}
