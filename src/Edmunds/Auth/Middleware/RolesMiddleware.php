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
use Edmunds\Bases\Http\Middleware\BaseMiddleware;
use Edmunds\Http\Request;
use Edmunds\Http\Response;

/**
 * Middleware for required roles
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
