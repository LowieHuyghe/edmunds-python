<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Foundation\Controllers;

use Edmunds\Bases\Http\Controllers\BaseController;

/**
 * Cron handler for Google App Engine
 */
class CronHandlerController extends BaseController
{
	/**
	 * Kernel
	 * @var Edmunds\Console\Kernel
	 */
	protected $kernel;

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		if ( ! $this->app->runningInConsole() || ! $this->request->getHeader('X-Appengine-Cron'))
		{
			abort(403);
		}

		$this->kernel = $this->app->make('kernel');
	}

	/**
	 * Run Task
	 * @param string $name
	 */
	public function runTask($name)
	{
		try
		{
			$exitCode = $this->kernel->call($name);

			$success = is_numeric($exitCode) ? ($exitCode == 0) : $exitCode;
			if ( ! $success)
			{
				$this->response->statusCode = 500;
			}
		}
		catch (\Exception $e)
		{
			$this->response->statusCode = 500;

			throw $e;
		}
		catch (\Throwable $e)
		{
			$this->response->statusCode = 500;

			throw $e;
		}
	}
}
