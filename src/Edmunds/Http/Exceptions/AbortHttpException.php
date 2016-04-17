<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Http\Exceptions;

use Edmunds\Bases\Exceptions\BaseException;

/**
 * Exception to stop the flow of the app and finish (instead of die and exit so logging can be done)
 */
class AbortHttpException extends BaseException
{
	//
}