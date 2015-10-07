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

namespace Core\Structures\Io;
use Core\Structures\BaseStructure;
use Illuminate\Validation\Validator;

/**
 * The validator for input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Validation extends BaseStructure
{
	/**
	 * Values to validate
	 * @var ValidationRule[]
	 */
	private $values = array();

	/**
	 * Input to validate
	 * @var array
	 */
	private $input;

	/**
	 * The constructor
	 * @param array $input
	 */
	public function __construct($input = null)
	{
		if ($input)
		{
			$this->input = $input;
		}
	}

	/**
	 * Explicitly set the input to validate
	 * @param array $input
	 */
	public function setInput($input)
	{
		$this->input = $input;
	}

	/**
	 * Get the validator with the current Input-data and rules
	 * @param string[] $names To check for only one specific argument
	 * @return Validator
	 */
	private function getValidator($names = null)
	{
		$rules = array();
		$sometimes = array();

		if (is_null($names))
		{
			//Check all rules
			foreach ($this->values as $name => $rule)
			{
				$values = $rule->rules;
				$vs = array();
				$sometimesSet = false;
				foreach ($values as $key => $value)
				{
					if ($key != 'sometimes')
					{
						$vs[] = $key . (is_null($value) ? '' : ":$value");
					}
					else
					{
						$sometimes[$name] = array('function' => $value);
						$sometimesSet = true;
					}
				}
				if (!$sometimesSet)
				{
					$rules[$name] = implode('|', $vs);
				}
				else
				{
					$sometimes[$name]['rules'] = implode('|', $vs);
				}
			}
		}
		else
		{
			//Check specific rules
			foreach ($names as $name)
			{
				if (!isset($this->values[$name]))
				{
					continue;
				}

				$values = $this->values[$name]->rules;
				$vs = array();
				$sometimesSet = false;
				foreach ($values as $key => $value)
				{
					if ($key != 'sometimes')
					{
						$vs[] = $key . (is_null($value) ? '' : ":$value");
					}
					else
					{
						$sometimes[$name] = array('function' => $value);
						$sometimesSet = true;
					}
				}
				if (!$sometimesSet)
				{
					$rules[$name] = implode('|', $vs);
				}
				else
				{
					$sometimes[$name]['rules'] = implode('|', $vs);
				}
			}
		}

		$validator = app('validator')->make($this->input, $rules);
		foreach ($sometimes as $name => $values)
		{
			$validator->sometimes($name, $values['rules'], $values['function']);
		}
		return $validator;
	}

	/**
	 * Check if validation has errors
	 * @param string[] $names To check for only one specific argument
	 * @return bool
	 */
	public function hasErrors($names = null)
	{
		$v = $this->getValidator($names);
		return $v->fails();
	}

	/**
	 * Return the validator with the errors
	 * @param string[] $names To check for only one specific argument
	 * @return Validator
	 */
	public function getErrors($names = null)
	{
		return $this->getValidator($names);
	}

	/**
	 * Get the value for a certain name
	 * @param string $name
	 * @return ValidationRule
	 */
	public function value($name)
	{
		if (!isset($this->values[$name]))
		{
			$this->values[$name] = new ValidationRule();
		}
		return $this->values[$name];
	}

}
