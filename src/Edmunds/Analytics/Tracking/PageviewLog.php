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
 * A pageview log
 *
 * @property  string $title
 */
class PageviewLog extends BaseLog
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->title = app()->getName();
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$validator->rule('title')->required();
	}
}
