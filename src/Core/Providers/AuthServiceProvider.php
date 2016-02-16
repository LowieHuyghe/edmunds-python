<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Core\Providers;

use Core\Auth\BasicStatefulGuard;
use Core\Auth\BasicStatelessGuard;
use Core\Auth\TokenGuard;
use Core\Bases\Providers\BaseServiceProvider;
use Exception;

/**
 * The auth service provider
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class AuthServiceProvider extends BaseServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerSessionGuard();
		$this->registerTokenGuard();
		$this->registerBasicGuard();
	}

	/**
	 * Register the session guard
	 */
	protected function registerSessionGuard()
	{
		$this->app['auth']->extend('session', function($app, $name, array $config)
		{
			if ($app->isStateless())
			{
				throw new Exception('Cannot use SessionGuard for authentication in a stateless application.');
			}

			return $app['auth']->createSessionDriver($name, $config);
		});
	}

	/**
	 * Register the token guard
	 */
	protected function registerTokenGuard()
	{
		$this->app['auth']->extend('token', function($app, $name, array $config)
		{
			return new TokenGuard(
				$app['auth']->createUserProvider($config['provider']),
				$app['request']
			);
		});
	}

	/**
	 * Register the basic guard
	 */
	protected function registerBasicGuard()
	{
		$this->app['auth']->extend('basic', function($app, $name, array $config)
		{
			if ($app->isStateless())
			{
				return new BasicStatelessGuard(
					$app['auth']->createUserProvider($config['provider']),
					$app['request']
				);
			}
			else
			{
				return new BasicStatefulGuard(
					$name,
					$app['auth']->createUserProvider($config['provider']),
					$app['session.store'],
					$app['request']
				);
			}
		});
	}
}
