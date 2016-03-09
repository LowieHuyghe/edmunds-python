<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Bases\Http\Middleware;

use Closure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Middleware base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class BaseMiddleware
{
	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request  $r
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($r, Closure $next)
	{
		return $next($r);
	}
}
