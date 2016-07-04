<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Concerns;

use Edmunds\Http\Exceptions\AbortHttpException;
use Edmunds\Http\Response;
use Edmunds\Registry;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * The RegistersExceptionHandlers concern
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

	/**
	 * Get the Monolog handler for the application.
	 *
	 * @return \Monolog\Handler\AbstractHandler
	 */
	protected function getMonologHandler()
	{
		$monologHandler = config('app.exceptions.monologhandler', null);

		// fetch class used for logging
		if (is_array($monologHandler))
		{
			$monologHandlerClass = $monologHandler['class'];
		}
		else
		{
			$monologHandlerClass = $monologHandler;
		}

		switch($monologHandlerClass)
		{
			case SyslogHandler::class:
				return (new SyslogHandler(
					isset($monologHandler['ident']) ? $monologHandler['ident'] : null,
					isset($monologHandler['facility']) ? $monologHandler['facility'] : LOG_USER,
					Logger::DEBUG))
						->setFormatter(new LineFormatter(null, null, true, true));

			case StreamHandler::class:
			default:
				return parent::getMonologHandler();

		}
	}
}
