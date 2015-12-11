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

namespace Core\Io\Validation;
use Core\Io\Translator;
use Illuminate\Support\Str;

/**
 * The validator for input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Validator extends \Illuminate\Validation\Validator
{
	/**
	 * Get the validation message for an attribute and rule.
	 * @param string $attribute
	 * @param string $rule
	 * @return string
	 */
	protected function getMessage($attribute, $rule)
	{
		$lowerRule = Str::snake($rule);

		//subrules for size-rules
		if (in_array($rule, $this->sizeRules))
		{
			$type = $this->getAttributeType($attribute);

			return "validation.{$lowerRule}.{$type}";
		}

		return "validation.{$lowerRule}";
	}

	/**
	 * Replace all error message place-holders with actual values.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function doReplacements($message, $attribute, $rule, $parameters)
	{
		$params = array();

		//Get the required params
		if (method_exists($this, $replacer = "replace{$rule}"))
		{
			$params = $this->$replacer($message, $attribute, $rule, $parameters);
		}

		//Add the attribute
		$params['attribute'] = $this->getAttribute($attribute);

		//Return the translated string
		return $this->translator->trans($message, $params);
	}

	/**
	 * Get the displayable name of the attribute.
	 * @param  string  $attribute
	 * @return string
	 */
	protected function getAttribute($attribute)
	{
		return $this->translator->trans($attribute);
	}

	/**
	 * Get the displayable name of the value.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return string
	 */
	public function getDisplayableValue($attribute, $value)
	{
		return $this->translator->trans($value);
	}

	/**
	 * Replace all place-holders for the between rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceBetween($message, $attribute, $rule, $parameters)
	{
		return array('min' => $parameters[0], 'max' => $parameters[1]);
	}

	/**
	 * Replace all place-holders for the digits rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceDigits($message, $attribute, $rule, $parameters)
	{
		return array('digits' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the digits (between) rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceDigitsBetween($message, $attribute, $rule, $parameters)
	{
		return $this->replaceBetween($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the size rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceSize($message, $attribute, $rule, $parameters)
	{
		return array('size' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the min rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceMin($message, $attribute, $rule, $parameters)
	{
		return array('min' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the max rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceMax($message, $attribute, $rule, $parameters)
	{
		return array('max' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the in rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceIn($message, $attribute, $rule, $parameters)
	{
		foreach ($parameters as &$parameter) {
			$parameter = $this->getDisplayableValue($attribute, $parameter);
		}

		return array('values' => implode(', ', $parameters));
	}

	/**
	 * Replace all place-holders for the not_in rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceNotIn($message, $attribute, $rule, $parameters)
	{
		return $this->replaceIn($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the mimes rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceMimes($message, $attribute, $rule, $parameters)
	{
		return array('values' => implode(', ', $parameters));
	}

	/**
	 * Replace all place-holders for the required_with rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceRequiredWith($message, $attribute, $rule, $parameters)
	{
		$parameters = $this->getAttributeList($parameters);

		return array('values' => implode(' / ', $parameters));
	}

	/**
	 * Replace all place-holders for the required_with_all rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceRequiredWithAll($message, $attribute, $rule, $parameters)
	{
		return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the required_without rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceRequiredWithout($message, $attribute, $rule, $parameters)
	{
		return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the required_without_all rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceRequiredWithoutAll($message, $attribute, $rule, $parameters)
	{
		return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the required_if rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceRequiredIf($message, $attribute, $rule, $parameters)
	{
		$parameters[1] = $this->getDisplayableValue($parameters[0], Arr::get($this->data, $parameters[0]));

		$parameters[0] = $this->getAttribute($parameters[0]);

		return array('other' => $parameters[0], 'value' => $parameters[1]);
	}

	/**
	 * Replace all place-holders for the same rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceSame($message, $attribute, $rule, $parameters)
	{
		return array('other' => $this->getAttribute($parameters[0]));
	}

	/**
	 * Replace all place-holders for the different rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceDifferent($message, $attribute, $rule, $parameters)
	{
		return $this->replaceSame($message, $attribute, $rule, $parameters);
	}

	/**
	 * Replace all place-holders for the date_format rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceDateFormat($message, $attribute, $rule, $parameters)
	{
		return array('format' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the before rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceBefore($message, $attribute, $rule, $parameters)
	{
		if (! (strtotime($parameters[0]))) {
			return array('date' => $this->getAttribute($parameters[0]));
		}

		return array('date' => $parameters[0]);
	}

	/**
	 * Replace all place-holders for the after rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceAfter($message, $attribute, $rule, $parameters)
	{
		return $this->replaceBefore($message, $attribute, $rule, $parameters);
	}
}