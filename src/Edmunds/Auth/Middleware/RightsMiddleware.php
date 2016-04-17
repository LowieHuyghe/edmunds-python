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

/**
 * Middleware for required rights
 */
class RightsMiddleware extends BaseMiddleware
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
			$rightIds = array_slice(func_get_args(), 2);

			foreach ($rightIds as $rightId)
			{
				if (!$auth->user->hasRight($rightId))
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
