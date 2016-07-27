<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Concerns;

use Edmunds\Analytics\AnalyticsManager;
use Edmunds\Http\Exceptions\AbortHttpException;
use Edmunds\Http\Response;
use Edmunds\Registry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * The routes request concern
 */
trait RoutesRequests
{
	/**
	 * Dispatch the incoming request.
	 *
	 * @param  SymfonyRequest|null $request
	 * @return Response
	 */
	public function dispatch($request = null)
	{
		// check down for maintenance
		if ($this->isDownForMaintenance())
		{
			try
			{
				abort(503);
			}
			catch (ServiceUnavailableHttpException $exception)
			{
				$response = $this->sendExceptionToHandler($exception);
			}
		}
		else
		{
			$response = parent::dispatch($request);
		}

		// log pageview and flush all logs
		$this->logPageView();
		Registry::warehouse()->flush();

		// attach extra's to response
		Response::getInstance()->attachExtras($response);

		return $response;
	}

	/**
	 * Handle a route found by the dispatcher.
	 *
	 * @param  array  $routeInfo
	 * @return mixed
	 */
	protected function handleFoundRoute($routeInfo)
	{
		if (isset($routeInfo[1]['uses']))
		{
			list($controller, $method) = explode('@', $routeInfo[1]['uses']);

			// change method
			$routeInfo[1]['uses'] = implode('@', array($controller, 'responseFlow'));

			// change parameters
			$routeInfo[2] = array($method, $routeInfo[2]);
		}

		return parent::handleFoundRoute($routeInfo);
	}
}
