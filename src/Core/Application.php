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

namespace Core;
use Core\Analytics\Tracking\PageviewLog;
use Core\Database\Migrations\Migrator;
use Core\Http\Exceptions\AbortHttpException;
use Core\Auth\Auth;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Http\Response;
use Core\Providers\HttpServiceProvider;
use Core\Registry;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Core\Foundation\Concerns\RoutesRequests;
use Core\Foundation\Concerns\RegistersExceptionHandlers;
use Core\Foundation\Concerns\StatefullBindingRegisterers;
use Core\Foundation\Concerns\FilesystemBindingRegisterers;
use Core\Foundation\Concerns\MailBindingRegisterers;

/**
 * The structure for application
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 */
class Application extends \Laravel\Lumen\Application
{
	use RoutesRequests;
	use RegistersExceptionHandlers;
	use StatefullBindingRegisterers;
	use FilesystemBindingRegisterers;
	use MailBindingRegisterers;

	/**
	 * Get the name of the app
	 * @return bool
	 */
	public function getName()
	{
		return config('app.name');
	}

	/**
	 * Check if stateful
	 * @return bool
	 */
	public function isStateful()
	{
		return config('app.stateful', true);
	}

	/**
	 * Get entrypoint
	 * @return string
	 */
	public function getEntrypoint()
	{
		return config('app.entrypoint', 'default');
	}

	/**
	 * Check if local environment
	 * @return bool
	 */
	public function isLocal()
	{
		return $this->environment('local');
	}

	/**
	 * Check if production environment
	 * @return bool
	 */
	public function isProduction()
	{
		return $this->environment('production');
	}

	/**
	 * Check if testing environment
	 * @return bool
	 */
	public function isTesting()
	{
		return $this->environment('testing');
	}

	/**
     * Determine if we are running unit tests.
     *
     * @return bool
     */
	public function runningUnitTests()
	{
		return $this->isTesting();
	}

	/**
	 * Get the path to the given configuration file.
	 *
	 * If no name is provided, then we'll return the path to the config folder.
	 *
	 * @param  string|null  $name
	 * @return string
	 */
	public function getConfigurationPath($name = null)
	{
		if (!$name)
		{
			$appConfigDir = $this->basePath('config').'/';

			if (file_exists($appConfigDir))
			{
				return $appConfigDir;
			}
			elseif (file_exists($ath = CORE_BASE_PATH . '/config/'))
			{
				return $path;
			}
			elseif ($path = parent::getConfigurationPath($name))
			{
				return $path;
			}
		}
		else
		{
			$appConfigPath = $this->basePath('config').'/'.$name.'.php';

			if (file_exists($appConfigPath))
			{
				return $appConfigPath;
			}
			elseif (file_exists($path = CORE_BASE_PATH . "/config/$name.php"))
			{
				return $path;
			}
			elseif ($path = parent::getConfigurationPath($name))
			{
				return $path;
			}
		}
	}

	/**
	 * Register container bindings for the application.
	 * @return void
	 */
	protected function registerUrlGeneratorBindings()
	{
		$this->singleton('url', function () {
			return new Routing\UrlGenerator($this);
		});
	}

	/**
	 * Register an available binding with the application.
	 *
	 * @param  string  $abstract
	 * @param  \Closure|string  $concrete
	 */
	public function bindAvailable($abstract, $concrete)
	{
		$abstract = $this->normalize($abstract);
		$concrete = $this->normalize($concrete);

		$this->availableBindings[$abstract] = $concrete;
	}

	/**
	 * Log the pageview
	 * @param Exception $exception
	 */
	protected function logPageView($exception = null)
	{
		if (!$this->runningInConsole())
		{
			(new PageviewLog())->log();
		}
	}

}
