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
 * The StatefullBindingRegisterers concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 */
trait StatefullBindingRegisterers
{
	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerCookieBindings()
	{
		$this->singleton('cookie', function () {
			return $this->loadComponent('session', 'Illuminate\Cookie\CookieServiceProvider', 'cookie');
		});
	}

	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerSessionBindings()
	{
		$this->singleton('session', function () {
			return $this->loadComponent('session', 'Illuminate\Session\SessionServiceProvider');
		});
		$this->singleton('session.store', function () {
			return $this->loadComponent('session', 'Illuminate\Session\SessionServiceProvider', 'session.store');
		});
	}
}
