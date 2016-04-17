<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Edmunds\Http\Client;

use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Http\Request;
use Edmunds\Validation\ValidationRule;
use Edmunds\Validation\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for the input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Input extends BaseStructure
{
	/**
	 * Instance of the response-helper
	 * @var Input
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return Input
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Input(Request::getInstance());
		}

		return self::$instance;
	}

	/**
	 * THe current request
	 * @var Request
	 */
	private $request;

	/**
	 * The validator
	 * @var Validator
	 */
	protected $validatorInstance;

	/**
	 * Constructor
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;
	}

	/**
	 * Fetch the validator
	 * @return Validator
	 */
	protected function getValidator()
	{
		if (!isset($this->validatorInstance))
		{
			// fetch input
			$input = $this->request->input() + $this->request->file();

			$this->validatorInstance = new Validator($input);
		}

		return $this->validatorInstance;
	}

	/**
	 * Fetch the rule for a certain name
	 * @param  string $name
	 * @return ValidationRule
	 */
	public function rule($name)
	{
		return $this->getValidator()->rule($name);
	}

	/**
	 * Check if input has errors
	 * @return bool
	 */
	public function hasErrors()
	{
		return $this->getValidator()->hasErrors();
	}

	/**
	 * Return the validator with the errors
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function getErrors()
	{
		return $this->getValidator()->getErrors();
	}

	/**
	 * Retrieve an input item from the request.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed|mixed[]
	 */
	public function get($key, $default = null)
	{
		return $this->getValidator()->get($key, $default);
	}

	/**
	 * Determine if the request contains a non-empty value for an input item.
	 *
	 * @param  string|string[]  $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->getValidator()->has($key);
	}

	/**
	 * Fetch all the data
	 * @return mixed[]
	 */
	public function all()
	{
		return $this->getValidator()->all();
	}

	/**
	 * Fetch certain of the data
	 * @param array $keys
	 * @return array
	 */
	public function only($keys)
	{
		return $this->getValidator()->only($keys);
	}

	/**
	 * Fetch all except of the data
	 * @param array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return $this->getValidator()->except($keys);
	}
}
