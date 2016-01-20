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

namespace Core\Http\Middleware;

use Core\Bases\Http\Middleware\BaseMiddleware;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Middleware for required rights
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
		$visitor = Visitor::getInstance();
		$request = Request::getInstance();
		$response = Response::getInstance();

		//Check if logged in
		$allowed = $visitor->loggedIn;
		//Check if has all rights
		if ($allowed)
		{
			foreach (Visitor::$requiredRights as $rightId)
			{
				if (!$visitor->user->hasRight($rightId))
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

		return $next($request);
	}
}
