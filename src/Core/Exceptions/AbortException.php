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

namespace Core\Exceptions;
use Core\Bases\Exceptions\BaseException;

/**
 * Exception to stop the flow of the app and finish (instead of die and exit so logging can be done)
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class AbortException extends BaseException
{
	/**
	 * Status of the abort
	 * @var int
	 */
	public $status;

	/**
	 * Constructor
	 * @param int $status
	 * @param string $message
	 */
	public function __construct($status = null, $message = null)
	{
		parent::__construct($message);

		$this->status = $status;
	}
}