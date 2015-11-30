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

use Core\Bases\Middleware\BaseMiddleware;

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
	 * THe current request
	 * @var Request
	 */
	private $request;

	/**
	 * THe current response
	 * @var Response
	 */
	private $response;

	/**
	 * The current visitor
	 * @var Visitor
	 */
	private $visitor;

	/**
	 * Contructor
	 * @param Request $request
	 * @param Response $response
	 * @param Visitor $visitor
	 */
	public function __construct(Request $request, Response $response, Visitor $visitor)
	{
		parent::__construct();

		$this->request = $request;
		$this->response = $response;
		$this->visitor = $visitor;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->visitor->loggedIn)
		{
			if ($this->request->ajax)
			{
				abort(403);
			}
			else
			{
				$this->response->responseRedirect(config('routing.loginroute'), null, true);
			}
		}

		return $next($request);
	}
}
