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

namespace LH\Core\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;

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
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->addValidationRules();
	}

	/**
	 * Add the validation of the model
	 */
	public function addValidationRules()
	{
		$this->validator->integer('id');

		$this->validator->required('name');
		$this->validator->max('name', 32);
	}

	/**
	 * Fetch all the defined constants
	 * @return array;
	 */
	private static function getConstants()
	{
		if (!isset(self::$constants[get_called_class()]))
		{
			$reflect = new ReflectionClass(get_called_class());
			self::$constants[get_called_class()] = $reflect->getConstants();
		}
		return self::$constants[get_called_class()];
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
		foreach ($constants as $name => $value)
		{
			$object = new self();
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
    			return collect(array($enum));
    		}
    	}

		return null;
    }

}
