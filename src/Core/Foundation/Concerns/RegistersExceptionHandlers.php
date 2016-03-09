<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Foundation\Concerns;

use Core\Http\Exceptions\AbortHttpException;
use Core\Http\Response;
use Core\Registry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * The RegistersExceptionHandlers concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 */
trait RegistersExceptionHandlers
{
	/**
	 * Throw an HttpException with the given data.
	 *
	 * @param  int     $code
	 * @param  string  $message
	 * @param  array   $headers
	 * @return void
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 */
	public function abort($code = 200, $message = '', array $headers = [])
	{
		switch($code)
		{
			case 200:
				throw new AbortHttpException($message);
			case 401:
				throw new UnauthorizedHttpException('Basic', $message);
			case 403:
				throw new AccessDeniedHttpException($message);
			case 404:
				throw new NotFoundHttpException($message);
			case 503:
				throw new ServiceUnavailableHttpException(null, $message);
			default:
				throw new HttpException($code, $message, null, $headers);
		}
	}
}
