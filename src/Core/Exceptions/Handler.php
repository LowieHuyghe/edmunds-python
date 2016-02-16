<?php

namespace Core\Exceptions;

use Core\Analytics\Tracking\ErrorLog;
use Core\Http\Response;
use Exception;
use Throwable;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
	 * Constructor
	 */
	public function __construct()
	{
		$this->dontReport = array_merge($this->dontReport, array(
			HttpException::class,
		));
	}

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
		$this->logException($e);

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
			(new ErrorLog())->log(config('app.analytics.errordefault', null));
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
				$response->render(null, $viewName);
				$response->assign('message', $e->getMessage());
				$response->statusCode = $e->getStatusCode();

				if ($e instanceof ServiceUnavailableHttpException)
				{
					$response->assign('maintenance', app()->isDownForMaintenance());
				}

				return $response->getResponse();
			}
		}
		catch(Exception $e){}
		catch(Throwable $e){}

		return parent::render($request, $e);
	}
}
