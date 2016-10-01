<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds;

use Edmunds\Analytics\Tracking\PageviewLog;
use Edmunds\Auth\Auth;
use Edmunds\Database\Migrations\Migrator;
use Edmunds\Foundation\Concerns\BindingRegisterers;
use Edmunds\Foundation\Concerns\GoogleAppEngine;
use Edmunds\Foundation\Concerns\RegistersExceptionHandlers;
use Edmunds\Foundation\Concerns\RoutesRequests;
use Edmunds\Foundation\Concerns\RuntimeEnvironment;
use Edmunds\Http\Client\Visitor;
use Edmunds\Http\Exceptions\AbortHttpException;
use Edmunds\Http\Request;
use Edmunds\Http\Response;
use Edmunds\Providers\HttpServiceProvider;
use Edmunds\Registry;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

/**
 * The structure for application
 */
class Application extends \Laravel\Lumen\Application
{
	use RuntimeEnvironment;
	use RoutesRequests;
	use RegistersExceptionHandlers;
	use BindingRegisterers;

	/**
	 * Get the name of the app
	 * @return bool
	 */
	public function getName()
	{
		return config('app.info.name');
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
	 * Bootstrap the application container.
	 *
	 * @return void
	 */
	protected function bootstrapContainer()
	{
		parent::bootstrapContainer();

		$this->registerAdditionalBindings();
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
		if ( ! $name)
		{
			$appConfigDir = $this->basePath('config').'/';

			if (file_exists($appConfigDir))
			{
				return $appConfigDir;
			}
			elseif (file_exists($ath = EDMUNDS_BASE_PATH . '/config/'))
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
			elseif (file_exists($path = EDMUNDS_BASE_PATH . "/config/$name.php"))
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
		if (config('app.analytics.autolog.pageview', false) && ! $this->runningInConsole())
		{
			(new PageviewLog())->log();
		}
	}

}
