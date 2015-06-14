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

namespace LH\Core\Helpers;
use Illuminate\Support\Facades\Validator;

/**
 * The helper to get controllers
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ValidationHelper extends BaseHelper
{
	/**
	 * Rules to validate
	 * @var array
	 */
	private $rules = array();

	/**
	 * The validator
	 * @var Validator
	 */
	private $validator;

	/**
	 * Input to validate
	 * @var array
	 */
	private $input;

	/**
	 * The constructor
	 * @param array $input
	 */
	function __construct($input = null)
	{
		if ($input)
		{
			$this->input = $input;
		}
	}


	/**
	 * Add a field to the validator's rules
	 * @param string $name
	 * @param string $key
	 * @param string $value
	 */
	private function add($name, $key, $value = null)
	{
		if (!isset($this->rules[$name]))
		{
			$this->rules[$name] = array();
		}

		$this->rules[$name][$key] = $value;
		unset($this->validator);
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
	 * @return Validator
	 */
	private function getValidator()
	{
		if (!isset($this->validator))
		{
			$rules = array();
			foreach ($this->rules as $name => $values)
			{
				$vs = array();
				foreach ($values as $key => $value)
				{
					$vs[] = $key . (is_null($value) ? '' : ":$value");
				}

				$rules[$name] = implode('|', $vs);
			}

			$this->validator = Validator::make([], $rules);
		}

		$this->validator->setData($this->input);
		return $this->validator;
	}

	/**
	 * Check if validation has errors
	 * @return bool
	 */
	public function hasErrors()
	{
		$v = $this->getValidator();
		return $v->fails();
	}

	/**
	 * Return the validator with the errors
	 * @return Validator
	 */
	public function getErrors()
	{
		return $this->getValidator();
	}

	/**
	 * The field under validation must be yes, on, or 1. This is useful for validating "Terms of Service" acceptance.
	 * @param string $name
	 */
	public function accepted($name)
	{
		$this->add($name, 'accepted');
	}
	/**
	 * The field under validation must be a valid URL according to the checkdnsrr PHP function.
	 * @param string $name
	 */
	public function activeUrl($name)
	{
		$this->add($name, 'active_url');
	}
	/**
	 * The field under validation must be a value after a given date. The dates will be passed into the PHP strtotime function.
	 * @param string $name
	 * @param int $date
	 */
	public function after($name, $date)
	{
		$this->add($name, 'after', $date);
	}
	/**
	 * The field under validation must be entirely alphabetic characters.
	 * @param string $name
	 */
	public function alpha($name)
	{
		$this->add($name, 'alpha');
	}
	/**
	 * The field under validation may have alpha-numeric characters, as well as dashes and underscores.
	 * @param string $name
	 */
	public function alphaDash($name)
	{
		$this->add($name, 'alpha_dash');
	}
	/**
	 * The field under validation must be entirely alpha-numeric characters.
	 * @param string $name
	 */
	public function alphaNum($name)
	{
		$this->add($name, 'alpha_num');
	}
	/**
	 * The field under validation must be of type array.
	 * @param string $name
	 */
	public function array_($name)
	{
		$this->add($name, 'array');
	}
	/**
	 * The field under validation must be a value preceding the given date. The dates will be passed into the PHP strtotime function.
	 * @param string $name
	 * @param int $date
	 */
	public function before($name, $date)
	{
		$this->add($name, 'before', $date);
	}
	/**
	 * The field under validation must have a size between the given min and max. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param string $name
	 * @param mixed $min
	 * @param mixed $max
	 */
	public function between($name, $min, $max)
	{
		$this->add($name, 'between', "$min,$max");
	}
	/**
	 * The field under validation must be able to be cast as a boolean. Accepted input are true, false, 1, 0, "1" and "0".
	 * @param string $name
	 */
	public function boolean($name)
	{
		$this->add($name, 'boolean');
	}
	/**
	 * The field under validation must have a matching field of foo_confirmation. For example, if the field under validation is password, a matching password_confirmation field must be present in the input.
	 * @param string $name
	 */
	public function confirmed($name)
	{
		$this->add($name, 'confirmed');
	}
	/**
	 * The field under validation must be a valid date according to the strtotime PHP function.
	 * @param string $name
	 */
	public function date($name)
	{
		$this->add($name, 'date');
	}
	/**
	 * The field under validation must match the format defined according to the date_parse_from_format PHP function.
	 * @param string $name
	 * @param string $format
	 */
	public function dateFormat($name, $format)
	{
		$this->add($name, 'date_format', $format);
	}
	/**
	 * The given field must be different than the field under validation.
	 * @param string $name
	 * @param string $field
	 */
	public function different($name, $field)
	{
		$this->add($name, 'different', $field);
	}
	/**
	 * The field under validation must be numeric and must have an exact length of value.
	 * @param string $name
	 * @param int $value
	 */
	public function digits($name, $value)
	{
		$this->add($name, 'digits', $value);
	}
	/**
	 * The field under validation must have a length between the given min and max.
	 * @param string $name
	 * @param mixed $min
	 * @param mixed $max
	 */
	public function digitsBetween($name, $min, $max)
	{
		$this->add($name, 'digits_between', "$min,$max");
	}
	/**
	 * The field under validation must be formatted as an e-mail address.
	 * @param string $name
	 */
	public function email($name)
	{
		$this->add($name, 'email');
	}
	/**
	 * The field under validation must exist on a given database table.
	 * 	Basic Usage Of Exists Rule
	 * 		'state' => 'exists:states'
	 * 	Specifying A Custom Column Name
	 * 		'state' => 'exists:states,abbreviation'
	 * 	You may also specify more conditions that will be added as "where" clauses to the query:
	 * 		'email' => 'exists:staff,email,account_id,1'
	 * 	Passing NULL as a "where" clause value will add a check for a NULL database value:
	 * 		'email' => 'exists:staff,email,deleted_at,NULL'
	 * @param string $name
	 */
	public function exists($name, $table, $column)
	{
		$this->add($name, 'exists', "$table,$column");
	}
	/**
	 * The file under validation must be an image (jpeg, png, bmp, or gif)
	 * @param string $name
	 */
	public function image($name)
	{
		$this->add($name, 'image');
	}
	/**
	 * The field under validation must be included in the given list of values.
	 * @param string $name
	 * @param array $list
	 */
	public function in($name, $list)
	{
		$this->add($name, 'in', implode(',', $list));
	}
	/**
	 * The field under validation must have an integer value.
	 * @param string $name
	 */
	public function integer($name)
	{
		$this->add($name, 'integer');
	}
	/**
	 * The field under validation must be formatted as an IP address.
	 * @param string $name
	 */
	public function ip($name)
	{
		$this->add($name, 'ip');
	}
	/**
	 * The field under validation must be less than or equal to a maximum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param string $name
	 * @param mixed $value
	 */
	public function max($name, $value)
	{
		$this->add($name, 'max', $value);
	}
	/**
	 * The file under validation must have a MIME type corresponding to one of the listed extensions.
	 * 	Basic Usage Of MIME Rule
	 * 		'photo' => 'mimes:jpeg,bmp,png'
	 * @param string $name
	 * @param array $list
	 */
	public function mimes($name, $list)
	{
		$this->add($name, 'mimes', implode(',', $list));
	}
	/**
	 * The field under validation must have a minimum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param string $name
	 * @param mixed $value
	 */
	public function min($name, $value)
	{
		$this->add($name, 'min', $value);
	}
	/**
	 * The field under validation must not be included in the given list of values.
	 * @param string $name
	 * @param array $list
	 */
	public function notIn($name, $list)
	{
		$this->add($name, 'not_in', implode(',', $list));
	}
	/**
	 * The field under validation must have a numeric value.
	 * @param string $name
	 */
	public function numeric($name)
	{
		$this->add($name, 'numeric');
	}
	/**
	 * The field under validation must match the given regular expression.
	 * 	Note: When using the regex pattern, it may be necessary to specify rules in an array instead of using pipe delimiters, especially if the regular expression contains a pipe character.
	 * @param string $name
	 * @param string $pattern
	 */
	public function regex($name, $pattern)
	{
		$this->add($name, 'regex', $pattern);
	}
	/**
	 * The field under validation must be present in the input data.
	 * @param string $name
	 */
	public function required($name)
	{
		$this->add($name, 'required');
	}
	/**
	 * The field under validation must be present if the field field is equal to any value.
	 * @param string $name
	 * @param string $field
	 * @param array $values
	 */
	public function requiredIf($name, $field, $values)
	{
		$this->add($name, 'required_if', "$field," . implode(',', $values));
	}
	/**
	 * The field under validation must be present only if any of the other specified fields are present.
	 * @param string $name
	 * @param array $list
	 */
	public function requiredWith($name, $list)
	{
		$this->add($name, 'required_with', implode(',', $list));
	}
	/**
	 * The field under validation must be present only if all of the other specified fields are present.
	 * @param string $name
	 * @param array $list
	 */
	public function requiredWithAll($name, $list)
	{
		$this->add($name, 'required_with_all', implode(',', $list));
	}
	/**
	 * The field under validation must be present only when any of the other specified fields are not present.
	 * @param string $name
	 * @param array $list
	 */
	public function requiredWithout($name, $list)
	{
		$this->add($name, 'required_without', implode(',', $list));
	}
	/**
	 * The field under validation must be present only when the all of the other specified fields are not present.
	 * @param string $name
	 * @param array $list
	 */
	public function requiredWithoutAll($name, $list)
	{
		$this->add($name, 'required_without_all', implode(',', $list));
	}
	/**
	 * The given field must match the field under validation.
	 * @param string $name
	 * @param string $field
	 */
	public function same($name, $field)
	{
		$this->add($name, 'same', $field);
	}
	/**
	 * The field under validation must have a size matching the given value. For string data, value corresponds to the number of characters. For numeric data, value corresponds to a given integer value. For files, size corresponds to the file size in kilobytes.
	 * @param string $name
	 */
	public function size($name, $value)
	{
		$this->add($name, 'size', $value);
	}
	/**
	 * The field under validation must be a valid timezone identifier according to the timezone_identifiers_list PHP function.
	 * @param string $name
	 * @param string $timezone
	 */
	public function timezone($name, $timezone)
	{
		$this->add($name, 'timezone', $timezone);
	}
	/**
	 * The field under validation must be unique on a given database table. If the column option is not specified, the field name will be used.
	 * 	Basic Usage Of Unique Rule
	 * 		'email' => 'unique:users'
	 * 	Specifying A Custom Column Name
	 * 		'email' => 'unique:users,email_address'
	 * 	Forcing A Unique Rule To Ignore A Given ID
	 * 		'email' => 'unique:users,email_address,10'
	 * 	Adding Additional Where Clauses
	 * 		You may also specify more conditions that will be added as "where" clauses to the query:
	 * 		'email' => 'unique:users,email_address,NULL,id,account_id,1'
	 * 	In the rule above, only rows with an account_id of 1 would be included in the unique check.
	 * @param string $name
	 * @param string $table
	 * @param string $column
	 * @param string $except
	 * @param array $where
	 */
	public function unique($name, $table, $column = null, $except = null, $where = array())
	{
		if (!empty($where))
		{
			$string = '';
			$first = true;
			foreach ($where as $key => $value)
			{
				if ($first)
				{
					$first = false;
				}
				else
				{
					$string .= ',';
				}
				$string .= "$key,$value";
			}

		}

		if (is_null($column) && is_null($except) && empty($where))
		{
			$rule = $table;
		}
		elseif (is_null($except) && empty($where))
		{
			$rule = "$table,$column";
		}
		elseif (empty($where))
		{
			$rule = "$table,$column,$except";
		}
		else
		{
			$rule = "$table,$column,$except,$where";
		}

		$this->add($name, 'unique', $rule);
	}
	/**
	 * The field under validation must be formatted as an URL.
	 * 	Note: This function uses PHP's filter_var method.
	 * @param string $name
	 */
	public function url($name)
	{
		$this->add($name, 'url');
	}

}
