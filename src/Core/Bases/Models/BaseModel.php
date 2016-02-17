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
use Core\Validation\Validation;
use Core\Localization\Format\DateTime;
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
	 * Array that represents the attributes that are objects
	 * Ex: 'location' => Location::class,
	 * @var array
	 */
	protected $recoverObjects = [];

	/**
	 * The required fields of this model
	 * @var array
	 */
	protected $required = [];

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
			$this->addRequiredValidationRules();
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
        $query = $this->newQueryWithoutScopes();

        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // Validate it all
		if ((!isset($options['validate']) || $options['validate']) && $this->hasErrors()) {
			return false;
		}

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->performUpdate($query, $options);
        }

        // If the model is brand new, we'll insert it into our database and set the
        // ID attribute on the model to the value of the newly inserted row's ID
        // which is typically an auto-increment value managed by the database.
        else {
            $saved = $this->performInsert($query, $options);
        }

        if ($saved) {
            $this->finishSave($options);
        }

        return $saved;
    }

    /**
     * Get a fresh timestamp for the model.
     *
     * @return DateTime
     */
    public function freshTimestamp()
    {
        return new DateTime;
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return DateTime
     */
    protected function asDateTime($value)
    {
        // If this value is already a DateTime instance, we shall just return it as is.
        // This prevents us having to reinstantiate a DateTime instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof DateTime) {
            return $value;
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof \DateTime) {
            return DateTime::instance($value);
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a DateTime object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return DateTime::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // DateTime instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting DateTimeized conversion.
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return DateTime::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the DateTime object
        // that is returned back out to the developers after we convert it here.
        return DateTime::createFromFormat($this->getDateFormat(), $value);
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
	 * Apply the required fields to validation
	 */
	protected function addRequiredValidationRules()
	{
		foreach ($this->required as $field)
		{
			$this->validator->value($field)->required();
		}
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
		$objectKeys = array_keys($model->recoverObjects);

		foreach ($attributes as $key => $value)
		{
			if (!in_array($key, $objectKeys))
			{
				$model->$key = $value;
			}
			else
			{
				$model->$key = call_user_func_array(array($model->recoverObjects[$key], 'recover'), array($value));
			}
		}

		return $model;
	}

	/**
	 * Get the required fields of a model
	 * @return array
	 */
	public static function getRequired()
	{
		return (new static())->required;
	}

}
