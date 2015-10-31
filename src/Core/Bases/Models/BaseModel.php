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

namespace Core\Bases\Models;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Core\Database\Relations\BelongsToManyEnums;
use Core\Database\Relations\HasOneEnum;
use Core\Io\Validation;
use Illuminate\Validation\Validator;

/**
 * A base for the models to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseModel extends Model
{
	/**
	 * The validator
	 * @var Validation
	 */
	private $validator;

	/**
	 * Enable or disable timestamps by default
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes = array())
	{
		parent::__construct($attributes);

		$this->validator = new Validation();
		static::addValidationRules($this->validator);
	}

	/**
	 * Check if model has errors
	 * @return bool
	 */
	public function hasErrors()
	{
		$this->validator->setInput($this->getAttributes());

		return $this->validator->hasErrors();
	}

	/**
	 * Return the validator with the errors
	 * @return Validator
	 */
	public function getErrors()
	{
		$this->validator->setInput($this->getAttributes());

		return $this->validator->getErrors();
	}

	/**
	 * Define a one-to-one-enum relationship.
	 *
	 * @param  string  $enumClass
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 * @return HasOneEnum
	 */
	public function hasOneEnum($enumClass, $foreignKey = null, $localKey = null)
	{
		$foreignKey = $foreignKey ?: $this->getForeignKey();

		$instance = new $enumClass();

		$localKey = $localKey ?: $this->getKeyName();

		return new HasOneEnum($instance->newQuery(), $this, $enumClass, $foreignKey, $localKey);
	}

	/**
	 * Define a many-to-many-enum relationship.
	 *
	 * @param  string  $enumClass
	 * @param  string  $table
	 * @param  string  $foreignKey
	 * @param  string  $otherKey
	 * @param  string  $relation
	 * @return BelongsToManyEnums
	 */
	public function belongsToManyEnums($enumClass, $table = null, $foreignKey = null, $otherKey = null, $relation = null)
	{
		// If no relationship name was passed, we will pull backtraces to get the
		// name of the calling function. We will use that function name as the
		// title of this relation since that is a great convention to apply.
		if (is_null($relation)) {
			$relation = $this->getBelongsToManyCaller();
		}

		// First, we'll need to determine the foreign key and "other key" for the
		// relationship. Once we have determined the keys we'll make the query
		// instances as well as the relationship instances we need for this.
		$foreignKey = $foreignKey ?: $this->getForeignKey();

		$instance = new $enumClass();

		return new BelongsToManyEnums($instance->newQuery(), $this, $enumClass, $table, $foreignKey, $otherKey, $relation);
	}

	/**
	 * Save the model to the database.
	 *
	 * @param  array  $options
	 * @return bool
	 */
	public function save(array $options = [])
	{
		if ($this->hasErrors())
		{
			return false;
		}

		return parent::save($options);
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		//
	}

	/**
	 * Create an instance of the model
	 * @param array $attributes
	 * @return BaseModel
	 */
	public static function dummy($attributes = array())
	{
		$className = get_called_class();

		$factory = app('Illuminate\Database\Eloquent\Factory');
		$factory->define($className, array($className, 'factory'));

		return factory($className)->make($attributes);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 * @throws \Exception
	 */
	protected static function factory($faker)
	{
		throw new \Exception('Factory not defined in ' . get_called_class());
	}

}
