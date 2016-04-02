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

namespace Core\Foundation\Concerns;

use Core\Analytics\AnalyticsManager;
use Core\Http\Exceptions\AbortHttpException;
use Core\Http\Response;
use Core\Registry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * The routes request concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
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
		// initializer
		if ($iniializerController = config('app.routing.initializer'))
		{
			$instance = $this->make($iniializerController);
			$method = 'initialize';

			$this->callControllerCallable([$instance, $method], array());
		}

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

		// check if analytics are enabled
		if (AnalyticsManager::isEnabled())
		{
			// log pageview and flush all logs
			if (config('app.analytics.autolog.pageview', false))
			{
				$this->logPageView();
			}
			Registry::warehouse()->flush();
		}

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
