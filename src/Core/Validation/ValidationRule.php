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
use Core\Localization\Format\DateTime;
use Core\Validation\Validator;

/**
 * A rule for validation
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property mixed $fallback The value to fallback on
 */
class ValidationRule extends BaseStructure
{
	/**
	 * Rules to validate
	 * @var array
	 */
	public $rules = array();

	/**
	 * The name of the column used
	 * @var string
	 */
	protected $column;

	/**
	 * The validator
	 * @var Validator
	 */
	protected $validator;

	/**
	 * Consrtuctor
	 * @param string $column
	 * @param Validator $validator
	 */
	public function __construct($column, $validator)
	{
		parent::__construct();

		$this->column = $column;
		$this->validator = $validator;
	}

	/**
	 * Get the value for this rule
	 * @return mixed
	 */
	public function get()
	{
		return $this->validator->get($this->column);
	}

	/**
	 * Set a fallback value
	 * @param  mixed $value
	 * @return ValidationRule
	 */
	public function fallback($value)
	{
		$this->fallback = $value;
		return $this;
	}

	/**
	 * Add a field to the validator's rules
	 * @param string $key
	 * @param string|callable $value
	 */
	private function add($key, $value = null)
	{
		$this->rules[$key] = $value;

		$this->validator->rebuildRules = true;
	}

	/**
	 * The field under validation must be yes, on, or 1. This is useful for validating "Terms of Service" acceptance.
	 * @return ValidationRule
	 */
	public function accepted()
	{
		$this->add('accepted');
		return $this;
	}
	/**
	 * The field under validation must be a valid URL according to the checkdnsrr PHP function.
	 * @return ValidationRule
	 */
	public function activeUrl()
	{
		$this->add('active_url');
		return $this;
	}
	/**
	 * The field under validation must be a value after a given date. The dates will be passed into the PHP strtotime function.
	 * @param int $date
	 * @return ValidationRule
	 */
	public function after($date)
	{
		$this->add('after', $date);
		return $this;
	}
	/**
	 * The field under validation must be entirely alphabetic characters.
	 * @return ValidationRule
	 */
	public function alpha()
	{
		$this->add('alpha');
		return $this;
	}
	/**
	 * The field under validation may have alpha-numeric characters, as well as dashes and underscores.
	 * @return ValidationRule
	 */
	public function alphaDash()
	{
		$this->add('alpha_dash');
		return $this;
	}
	/**
	 * The field under validation must be entirely alpha-numeric characters.
	 * @return ValidationRule
	 */
	public function alphaNum()
	{
		$this->add('alpha_num');
		return $this;
	}
	/**
	 * The field under validation must be of type array.
	 * @return ValidationRule
	 */
	public function array_()
	{
		$this->add('array');
		return $this;
	}
	/**
	 * The field under validation must be a value preceding the given date. The dates will be passed into the PHP strtotime function.
	 * @param int $date
	 * @return ValidationRule
	 */
	public function before($date)
	{
		$this->add('before', $date);
		return $this;
	}
	/**
	 * The field under validation must have a size between the given min and max. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param mixed $min
	 * @param mixed $max
	 * @return ValidationRule
	 */
	public function between($min, $max)
	{
		$this->add('between', "$min,$max");
		return $this;
	}
	/**
	 * The field under validation must be able to be cast as a boolean. Accepted input are true, false, 1, 0, "1" and "0".
	 * @return ValidationRule
	 */
	public function boolean()
	{
		$this->add('boolean');
		return $this;
	}
	/**
	 * The field under validation must have a matching field of foo_confirmation. For example, if the field under validation is password, a matching password_confirmation field must be present in the input.
	 * @return ValidationRule
	 */
	public function confirmed()
	{
		$this->add('confirmed');
		return $this;
	}
	/**
	 * The field under validation must be a valid date according to the strtotime PHP function.
	 * @return ValidationRule
	 */
	public function date()
	{
		$this->add('date');
		return $this;
	}
	/**
	 * The field under validation must match the format defined according to the date_parse_from_format PHP function.
	 * @param string $format
	 * @return ValidationRule
	 */
	public function dateFormat($format)
	{
		$this->add('date_format', $format);
		return $this;
	}
	/**
	 * The given field must be different than the field under validation.
	 * @param string $field
	 * @return ValidationRule
	 */
	public function different($field)
	{
		$this->add('different', $field);
		return $this;
	}
	/**
	 * The field under validation must be numeric and must have an exact length of value.
	 * @param int $value
	 * @return ValidationRule
	 */
	public function digits($value)
	{
		$this->add('digits', $value);
		return $this;
	}
	/**
	 * The field under validation must have a length between the given min and max.
	 * @param mixed $min
	 * @param mixed $max
	 * @return ValidationRule
	 */
	public function digitsBetween($min, $max)
	{
		$this->add('digits_between', "$min,$max");
		return $this;
	}
	/**
	 * The field under validation must be formatted as an e-mail address.
	 * @return ValidationRule
	 */
	public function email()
	{
		$this->add('email');
		return $this;
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
	 * @param string $table
	 * @param string $column
	 * @return ValidationRule
	 */
	public function exists($table, $column)
	{
		$this->add('exists', "$table,$column");
		return $this;
	}
	/**
	 * The file under validation must be an image (jpeg, png, bmp, or gif)
	 * @return ValidationRule
	 */
	public function image()
	{
		$this->add('image');
		return $this;
	}
	/**
	 * The field under validation must be included in the given list of values.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function in($list)
	{
		$this->add('in', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must have an integer value.
	 * @return ValidationRule
	 */
	public function integer()
	{
		$this->add('integer');
		return $this;
	}
	/**
	 * The field under validation must be formatted as an IP address.
	 * @return ValidationRule
	 */
	public function ip()
	{
		$this->add('ip');
		return $this;
	}
	/**
	 * The field under validation must a valid JSON string.
	 * @return ValidationRule
	 */
	public function json()
	{
		$this->add('json');
		return $this;
	}
	/**
	 * The field under validation must be less than or equal to a maximum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param mixed $value
	 * @return ValidationRule
	 */
	public function max($value)
	{
		$this->add('max', $value);
		return $this;
	}
	/**
	 * The file under validation must have a MIME type corresponding to one of the listed extensions.
	 * 	Basic Usage Of MIME Rule
	 * 		'photo' => 'mimes:jpeg,bmp,png'
	 * @param array $list
	 * @return ValidationRule
	 */
	public function mimes($list)
	{
		$this->add('mimes', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must have a minimum value. Strings, numerics, and files are evaluated in the same fashion as the size rule.
	 * @param mixed $value
	 * @return ValidationRule
	 */
	public function min($value)
	{
		$this->add('min', $value);
		return $this;
	}
	/**
	 * The field under validation must not be included in the given list of values.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function notIn($list)
	{
		$this->add('not_in', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must have a numeric value.
	 * @return ValidationRule
	 */
	public function numeric()
	{
		$this->add('numeric');
		return $this;
	}
	/**
	 * The field under validation must match the given regular expression.
	 * 	Note: When using the regex pattern, it may be necessary to specify rules in an array instead of using pipe delimiters, especially if the regular expression contains a pipe character.
	 * @param string $pattern
	 * @return ValidationRule
	 */
	public function regex($pattern)
	{
		$this->add('regex', $pattern);
		return $this;
	}
	/**
	 * The field under validation must be present in the input data.
	 * @return ValidationRule
	 */
	public function required()
	{
		$this->add('required');
		return $this;
	}
	/**
	 * The field under validation must be present if the field field is equal to any value.
	 * @param string $field
	 * @param array $values
	 * @return ValidationRule
	 */
	public function requiredIf($field, $values)
	{
		$this->add('required_if', "$field," . implode(',', $values));
		return $this;
	}
	/**
	 * The field under validation must be present unless the anotherfield field is equal to any value.
	 * @param string $field
	 * @param array $values
	 * @return ValidationRule
	 */
	public function requiredUnless($field, $values)
	{
		$this->add('required_unless', "$field," . implode(',', $values));
		return $this;
	}
	/**
	 * The field under validation must be present only if any of the other specified fields are present.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function requiredWith($list)
	{
		$this->add('required_with', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must be present only if all of the other specified fields are present.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function requiredWithAll($list)
	{
		$this->add('required_with_all', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must be present only when any of the other specified fields are not present.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function requiredWithout($list)
	{
		$this->add('required_without', implode(',', $list));
		return $this;
	}
	/**
	 * The field under validation must be present only when the all of the other specified fields are not present.
	 * @param array $list
	 * @return ValidationRule
	 */
	public function requiredWithoutAll($list)
	{
		$this->add('required_without_all', implode(',', $list));
		return $this;
	}
	/**
	 * The given field must match the field under validation.
	 * @param string $field
	 * @return ValidationRule
	 */
	public function same($field)
	{
		$this->add('same', $field);
		return $this;
	}
	/**
	 * The field under validation must have a size matching the given value. For string data, value corresponds to the number of characters. For numeric data, value corresponds to a given integer value. For files, size corresponds to the file size in kilobytes.
	 * @param int $value
	 * @return ValidationRule
	 */
	public function size($value)
	{
		$this->add('size', $value);
		return $this;
	}
	/**
	 * The field under validation must be a string.
	 * @param int $value
	 * @return ValidationRule
	 */
	public function string($value)
	{
		$this->add('string', $value);
		return $this;
	}
	/**
	 * The field under validation must be a valid timezone identifier according to the timezone_identifiers_list PHP function.
	 * @param string $timezone
	 * @return ValidationRule
	 */
	public function timezone($timezone)
	{
		$this->add('timezone', $timezone);
		return $this;
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
	 * @param string $table
	 * @param string $premiumKey Will add an exception for the given premiumKey
	 * @param string $column
	 * @param array $where
	 * @return ValidationRule
	 */
	public function unique($table, $premiumKey = null, $column = null, $where = array())
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
			$where = $string;
		}

		if (is_null($column))
		{
			$column = $this->column;
		}

		$this->add('unique', array($table, $column, $premiumKey, $where));
		return $this;
	}
	/**
	 * The field under validation must be formatted as an URL.
	 * 	Note: This function uses PHP's filter_var method.
	 * @return ValidationRule
	 */
	public function url()
	{
		$this->add('url');
		return $this;
	}
	/**
	 * The field will only be checked when the function returns true
	 * @param callable $function
	 * @return ValidationRule
	 */
	public function sometimes($function)
	{
		$this->add('sometimes', $function);
		return $this;
	}

}
