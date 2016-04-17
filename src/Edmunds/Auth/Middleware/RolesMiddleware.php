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
use Edmunds\Bases\Http\Middleware\BaseMiddleware;
use Edmunds\Http\Request;
use Edmunds\Http\Response;

/**
 * Middleware for required roles
 */
class RolesMiddleware extends BaseMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $r
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($r, \Closure $next)
	{
		$auth = Auth::getInstance();

		// check if logeed in
		$allowed = $auth->loggedIn;

		if ($allowed)
		{
			$roleIds = array_slice(func_get_args(), 2);

			foreach ($roleIds as $roleId)
			{
				if (!$auth->user->hasRole($roleId))
				{
					$allowed = false;
					break;
				}
			}
		}

		if (!$allowed)
		{
			abort(403);
		}

		return $next($r);
	}
}
