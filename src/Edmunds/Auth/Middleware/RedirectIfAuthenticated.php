<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Middleware;

use Closure;
use Edmunds\Auth\Auth;
use Edmunds\Bases\Http\Middleware\BaseMiddleware;

/**
 * Redirect if authenticated middleware
 */
class RedirectIfAuthenticated extends BaseMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$auth = Auth::getInstance();

		if ($auth->loggedIn)
		{
			$response = Response::getInstance();

			$response->redirect(config('app.auth.redirects.login', '/'));
			return;
		}

		return $next($request);
	}
}