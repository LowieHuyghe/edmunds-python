<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Analytics\Tracking;

use Edmunds\Bases\Analytics\Tracking\BaseLog;

/**
 * An event log
 *
 * @property string $category
 * @property string $action
 * @property string $name
 * @property mixed $value
 */
class EventLog extends BaseLog
{
	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$validator->rule('category')->required();
		$validator->rule('action')->required();
		$validator->rule('name')->required();
		$validator->rule('value')->required();
	}
}
