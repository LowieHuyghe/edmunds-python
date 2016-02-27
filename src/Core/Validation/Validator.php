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

namespace Core\Validation;
use Core\Bases\Structures\BaseStructure;
use Core\Localization\Translator;
use Core\Validation\Validatornnn;
use Illuminate\Validation\DatabasePresenceVerifier;

/**
 * The validator for input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property array $input Input to validate
 * @property boolean $rebuildRules Boolean to check if rules need to be rebuilded
 */
class Validator extends BaseStructure
{
	/**
	 * Rules to validate
	 * @var ValidationRule[]
	 */
	protected $rules = array();

	/**
	 * Input to validate
	 * @var array
	 */
	protected $input;

	/**
	 * The validation
	 * @var Validation
	 */
	protected $validation;

	/**
	 * The constructor
	 * @param array $input
	 */
	public function __construct($input = array())
	{
		parent::__construct();

		$this->input = $input;
		$this->validation = new Validation(Translator::getInstance(), $input, array());
		$this->rebuildRules = true;
	}

	/**
	 * Get the validator with the current Input-data and rules
	 * @return Validation
	 */
	protected function getValidation()
	{
		if ($this->rebuildRules)
		{
			$this->rebuildRules($this->validation);
			$this->rebuildRules = false;
		}

		return $this->validation;
	}

	/**
	 * Rebuild the rules for validation
	 * @param Validation $validation
	 */
	protected function rebuildRules(&$validation)
	{
		$rules = array();
		$sometimes = array();

		//Check all rules
		foreach ($this->rules as $name => $rule)
		{
			$values = $rule->rules;
			$vs = array();
			$sometimesSet = false;
			foreach ($values as $key => $value)
			{
				if ($key != 'sometimes')
				{
					if ($key == 'unique')
					{
						$value = $value[0] . ',' . $value[1] . (($value[2] && isset($this->input[$value[2]]) && $this->input[$value[2]]) ? (',' . $this->input[$value[2]]) : '') . ($value[3] ? (',' . $value[3]) : '');
					}
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

		// set rules
		$validation->setRules($rules);
		foreach ($sometimes as $name => $values)
		{
			$validation->sometimes($name, $values['rules'], $values['function']);
		}
	}

	/**
	 * Set the input
	 * @param array $input
	 */
	public function setInput($input)
	{
		$this->input = $input;
		$this->validation->setData($input);

		$this->rebuildRules = true;
	}

	/**
	 * Check if validation has errors
	 * @param string[] $names To check for only one specific argument
	 * @return bool
	 */
	public function hasErrors($names = null)
	{
		$v = $this->getValidation($names);
		return $v->fails();
	}

	/**
	 * Return the validator with the errors
	 * @param string[] $names To check for only one specific argument
	 * @return Validation
	 */
	public function getErrors($names = null)
	{
		return $this->getValidation($names);
	}

	/**
	 * Get the rule for a certain name
	 * @param string|null $name
	 * @return ValidationRule|ValidationRule[]
	 */
	public function rule($name)
	{
		if (is_null($name))
		{
			return $this->rules;
		}
		if (!isset($this->rules[$name]))
		{
			$this->rules[$name] = new ValidationRule($name, $this);
		}
		return $this->rules[$name];
	}

	/**
	 * Get the value
	 * @param  string $name
	 * @param  mixed $default
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		if (!isset($this->input[$name]))
		{
			$fallback = isset($this->rules[$name]) ? $this->rules[$name]->fallback : null;
			$value = $default ?: $fallback;
		}
		else
		{
			$value = $this->input[$name];

			// parse
			if (isset($this->rules[$name]))
			{
				$rules = $this->rules[$name]->rules;

				if (array_key_exists('boolean', $rules))
				{
					$value = boolval($value);
				}
				elseif (array_key_exists('integer', $rules))
				{
					$value = intval($value);
				}
				elseif (array_key_exists('numeric', $rules))
				{
					$value = floatval($value);
				}
				elseif (array_key_exists('date_format', $rules))
				{
					if (! $value instanceof DateTime)
					{
						$value = DateTime::createFromFormat($rules['date_format'], $value);
					}
				}
				elseif (array_key_exists('date', $rules))
				{
					if (! $value instanceof DateTime)
					{
						$value = DateTime::createFromDate($value);
					}
				}
			}
		}

		return $value;
	}

	/**
	 * Check if has value
	 * @param  string  $key
	 * @return boolean
	 */
	public function has($key)
	{
		return isset($this->input[$key]);
	}

	/**
	 * Fetch all values
	 * @return array
	 */
	public function all()
	{
		$all = array();

		foreach (array_keys($this->input) as $key)
		{
			$all[$key] = $this->get($key);
		}

		return $all;
	}

	/**
	 * Fetch values of matching keys
	 * @param  array $keys
	 * @return array
	 */
	public function only($keys)
	{
		return array_only($this->all(), is_array($keys) ? $keys : func_get_args());
	}

	/**
	 * Fetch values of all except keys
	 * @param  array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return array_except($this->all(), is_array($keys) ? $keys : func_get_args());
	}
}
