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

use Core\Bases\Providers\BaseServiceProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * The statefull service provider
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class StatefullServiceProvider extends BaseServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// register if not stateless
		if (!$this->app->isStateless())
		{
			$this->registerCookies();
			$this->registerSessions();
		}
	}

	/**
	 * Register cookies
	 */
	protected function registerCookies()
	{
		// aliases
		$this->app->alias('cookie', 'Illuminate\Contracts\Cookie\Factory');
		$this->app->alias('cookie', 'Illuminate\Contracts\Cookie\QueueingFactory');

		// bindings
		$this->app->bindAvailable('cookie', 'registerCookieBindings');
		$this->app->bindAvailable('Illuminate\Contracts\Cookie\Factory', 'registerCookieBindings');
		$this->app->bindAvailable('Illuminate\Contracts\Cookie\QueueingFactory', 'registerCookieBindings');

		// middleware
		$this->app->middleware(array(
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
		));
	}

	/**
	 * Register sessions
	 */
	protected function registerSessions()
	{
		// aliases
		$this->app->alias('session', 'Illuminate\Session\SessionManager');

		// bindings
		$this->app->bindAvailable('session', 'registerSessionBindings');
		$this->app->bindAvailable('session.store', 'registerSessionBindings');
		$this->app->bindAvailable('Illuminate\Session\SessionManager', 'registerSessionBindings');

		// middleware
		$this->app->middleware(array(
			StartSession::class,
			ShareErrorsFromSession::class,
		));
	}
}
