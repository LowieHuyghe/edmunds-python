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
use Core\Database\Relations\BelongsToEnum;
use Core\Database\Relations\BelongsToManyEnums;
use Core\Io\Validation\Validation;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
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
	protected $validator;

	/**
	 * Enable or disable timestamps by default
	 * @var boolean
	 */
	public $timestamps = true;

	/**
	 * Array that represents the attributes that are models
	 * Ex: 'location' => Location::class,
	 * @var array
	 */
	protected $models = [];

	/**
	 * The attributes that should be mutated to dates.
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes = array())
	{
		parent::__construct($attributes);

		if (!isset($this->validator))
		{
			$this->validator = new Validation();
			$this->addValidationRules();
		}
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
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $enumClass
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  string  $relation
     * @return BelongsToEnum
     */
    public function belongsToEnum($enumClass, $foreignKey = null, $otherKey = null, $relation = null)
    {
        // If no relation name was given, we will use this debug backtrace to extract
        // the calling method's name and use that as the relationship name as most
        // of the time this will be what we desire to use for the relationships.
        if (is_null($relation)) {
            list($current, $caller) = debug_backtrace(false, 2);

            $relation = $caller['function'];
        }

        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        if (is_null($foreignKey)) {
            $foreignKey = snake_case($relation).'_id';
        }

        $instance = new $enumClass();

        $otherKey = $otherKey ?: $instance->getKeyName();

        return new BelongsToEnum($instance->newQuery(), $this, $enumClass, $foreignKey, $otherKey, $relation);
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
	 */
	protected function addValidationRules()
	{
		$this->validator->value('created_at')->date();
		$this->validator->value('updated_at')->date();
		$this->validator->value('deleted_at')->date();
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

	/**
	 * Recover instance from array of attributes
	 * @param  array $attributes
	 * @return BaseModel
	 */
	public static function recover($attributes)
	{
		$modelClass = get_called_class();
		$model = new $modelClass();
		$modelKeys = array_keys($model->models);

		foreach ($attributes as $key => $value)
		{
			if (!in_array($key, $modelKeys))
			{
				$model->$key = $value;
			}
			else
			{
				$model->$key = call_user_func_array(array($model->models[$key], 'recover'), array($value));
			}
		}

		return $model;
	}

}
