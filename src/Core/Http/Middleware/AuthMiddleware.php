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
		if (!$this->visitor->loggedIn)
		{
			if ($this->request->ajax)
			{
				abort(403);
			}
			else
			{
				$this->response->redirect(config('app.routing.loginroute'), null, true);
			}
		}

		return $next($r);
	}
}
