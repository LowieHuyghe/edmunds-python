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
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use LH\Core\Database\Relations\BelongsToManyEnums;
use LH\Core\Database\Relations\HasOneEnum;
use LH\Core\Database\Relations\HasOneEnums;
use LH\Core\Helpers\ModelFactoryHelper;
use LH\Core\Helpers\ValidationHelper;
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
	 * @var ValidationHelper
	 */
	protected $validator;

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

		$this->validator = new ValidationHelper();
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

		$instance = new BaseModel();

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

		$instance = new BaseModel();

		// Now we're ready to create a new query builder for the related model and
		// the relationship instances for the relation. The relations will set
		// appropriate query constraint and entirely manages the hydrations.
		$query = $instance->newQuery();

		return new BelongsToManyEnums($query, $this, $enumClass, $table, $foreignKey, $otherKey, $relation);
	}

	/**
	 * Add the validation of the model
	 * @param ValidationHelper $validator
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
		return ModelFactoryHelper::createModel(get_called_class(), array(get_called_class(), 'factory'), $attributes);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		throw new \Exception('Factory not defined in ' . get_called_class());
	}

}
