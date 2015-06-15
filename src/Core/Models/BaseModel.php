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
use Illuminate\Database\Eloquent\Model;
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
