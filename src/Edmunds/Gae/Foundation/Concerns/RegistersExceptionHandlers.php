<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Foundation\Concerns;

use Edmunds\Http\Response;
use Error;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * The RegistersExceptionHandlers concern
 */
trait RegistersExceptionHandlers
{
	/**
	 * Get the Google App Engine Monolog handler for the application.
	 *
	 * @return \Monolog\Handler\AbstractHandler
	 */
	protected function getMonologHandler()
	{
		return (new SyslogHandler(
			null,
			LOG_USER,
			Logger::DEBUG))
				->setFormatter(new LineFormatter(null, null, true, true));
	}

	/**
	 * Handle an uncaught exception instance.
	 *
	 * @param  \Throwable  $e
	 * @return void
	 */
	protected function handleUncaughtException($e)
	{
		$handler = $this->resolveExceptionHandler();

		if ($e instanceof Error)
		{
			$e = new FatalThrowableError($e);
		}

		$handler->report($e);

		$handler->render($this->make('request'), $e)->send();
	}
}