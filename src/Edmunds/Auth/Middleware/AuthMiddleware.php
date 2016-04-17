<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Middleware;

use Edmunds\Auth\Auth;
use Edmunds\Auth\Guards\BasicStatefulGuard;
use Edmunds\Auth\Guards\BasicStatelessGuard;
use Edmunds\Bases\Http\Middleware\BaseMiddleware;
use Edmunds\Http\Request;
use Edmunds\Http\Response;

/**
 * Middleware for authentication
 */
class AuthMiddleware extends BaseMiddleware
{
	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $r
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($r, \Closure $next)
	{
		$auth = Auth::getInstance();
		$request = Request::getInstance();
		$response = Response::getInstance();

		// check if guest
		if (!$auth->loggedIn)
		{
			$guard = $auth->getGuard();
			if ($guard instanceof BasicStatefulGuard
				|| $guard instanceof BasicStatelessGuard)
			{
				abort(401);
			}
			elseif ($request->ajax
				|| $request->json
				|| $request->xml)
			{
				abort(403);
			}
			else
			{
				$response->redirect(config('app.routing.loginroute'), true);
			}
		}

		return $next($r);
	}
}
