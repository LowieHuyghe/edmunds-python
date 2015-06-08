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
use Core\Helpers\ValidationHelper;
use Illuminate\Validation\Validator;

/**
 * A base for the models to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property array[] $errors Attribute with the errors in
 *
 * @method void save() Creates or updates the object in the database
 * @method void delete() Deletes the object in the database
 *
 * @method hasOne hasOne(string $type) One-to-one relation
 * @method belongsTo belongsTo(string $type) One-to-one/Many-to-one relation
 * @method hasMany hasMany(string $type) One-to-many relation
 * @method belongsToMany belongsToMany(string $type) Many-to-many relation
 * @method hasManyThrough hasManyThrough(string $targetType, string $throughType) Has-many-through relation
 * @method morphTo morphTo() Polymorphic Relations
 * @method morphMany morphMany(string $type, string $method) Polymorphic Relations
 */
class BaseModel extends \Eloquent
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
	 */
	public function __construct()
	{
		parent::__construct();

		$this->validator = new ValidationHelper();
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

}
