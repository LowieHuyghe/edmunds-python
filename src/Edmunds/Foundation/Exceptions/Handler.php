<?php

namespace Edmunds\Foundation\Exceptions;

use Edmunds\Analytics\Tracking\ErrorLog;
use Edmunds\Http\Exceptions\AbortHttpException;
use Edmunds\Http\Response;
use Exception;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		$this->dontReport = array_unique(array_merge($this->dontReport, array(
			AbortHttpException::class,
		)));

		if (config('app.analytics.autolog.exceptions', false))
		{
			$this->logException($e);
		}

		parent::report($e);
	}

	/**
	 * Log the exception
	 * @param  Exception $e
	 */
	protected function logException(Exception $e)
	{
		try
		{
			(new ErrorLog())->log(config('app.analytics.errordefault', config('app.analytics.default', null)));
		}
		catch(Exception $e){}
		catch(Throwable $e){}
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		// aborted request
		if ($e instanceof AbortHttpException)
		{
			return Response::getInstance()->getResponse();
		}

		// render the exception
		try
		{
			if (($e instanceof UnauthorizedHttpException
					|| $e instanceof AccessDeniedHttpException
					|| $e instanceof NotFoundHttpException
					|| $e instanceof ServiceUnavailableHttpException)
				&& view()->exists($viewName = 'errors.' . $e->getStatusCode()))
			{
				$response = Response::getInstance();

				$response->header($e->getHeaders());
				$response->view(null, $viewName);
				$response->assign('message', $e->getMessage());
				$response->statusCode = $e->getStatusCode();

				if ($e instanceof ServiceUnavailableHttpException)
				{
					$response->assign('maintenance', app()->isDownForMaintenance());
				}

				return $response->getResponse();
			}
		}
		// overwrite exception
		catch(Exception $e){}
		catch(Throwable $e){}

		// show render page
		return parent::render($request, $e);
	}
}
