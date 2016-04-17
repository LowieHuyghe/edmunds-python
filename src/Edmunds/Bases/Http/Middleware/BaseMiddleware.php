<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Http\Middleware;

use Closure;
use Edmunds\Http\Client\Visitor;
use Edmunds\Http\Request;
use Edmunds\Http\Response;

/**
 * Middleware base to extend from
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
