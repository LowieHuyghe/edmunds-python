<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Providers;

use Edmunds\Bases\Providers\BaseServiceProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * The statefull service provider
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
		// register if stateful
		if ($this->app->isStateful())
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
