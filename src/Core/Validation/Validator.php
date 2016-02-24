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
 */
class Validator extends BaseStructure
{
	/**
	 * Values to validate
	 * @var ValidationRule[]
	 */
	protected $values = array();

	/**
	 * The constructor
	 * @param array $input
	 */
	public function __construct($input = array())
	{
		parent::__construct();

		$this->input = $input;
	}

	/**
	 * Get the validator with the current Input-data and rules
	 * @param string[] $names To check for only one specific argument
	 * @return Validation
	 */
	protected function getValidation($names = null)
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
		}

		//Make validator
		$validator = new Validation(Translator::getInstance(), $this->input, $rules);
		if (!isset(app()['validation.presence']))
		{
			$this->registerPresenceVerifier();
		}
		$validator->setPresenceVerifier(app()['validation.presence']);

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
	 * Get the value for a certain name
	 * @param string $name
	 * @return ValidationRule
	 */
	public function value($name)
	{
		if (!isset($this->values[$name]))
		{
			$this->values[$name] = new ValidationRule($name, $this);
		}
		return $this->values[$name];
	}

    /**
     * Register the database presence verifier.
     * @return void
     */
    protected function registerPresenceVerifier()
    {
        app()->singleton('validation.presence', function () {
            return new DatabasePresenceVerifier(app()['db']);
        });
    }

    /**
     * Get the value
     * @param  string $value
     * @return mixed
     */
    public function get($value)
    {
		return isset($this->values[$key]) ? $this->values[$key]->get() : ($this->input[$key] ?? null);
    }

    /**
     * Fetch all values
     * @return array
     */
	public function all()
	{
		$all = array();

		foreach (array_merge(array_keys($this->input) , array_keys($this->values)) as $key)
		{
			$all[$key] = isset($this->values[$key]) ? $this->values[$key]->get() : $this->input[$key];
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
