<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
