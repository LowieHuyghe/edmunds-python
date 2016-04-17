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
use Exception;

/**
 * An error log
 *
 * @property string $type
 * @property Exception $exception
 */
class ErrorLog extends BaseLog
{
	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->input->rule('type')->required();
		$this->input->rule('exception')->required();
	}
}
