<?php

namespace Core\Exceptions;

use Core\Analytics\Tracking\GA\ExceptionLog;
use Core\Analytics\NewRelic;
use Core\Http\Response;
use Exception;
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
		$message = $e->getMessage() ?: last(explode('\\', get_class($e)));
		// $file = str_replace(base_path(), '', $e->getFile());
		// $line = $e->getLine();
		// $trace = $e->getTrace();

		NewRelic::getInstance()->noticeError($message, $e);

		// $log = new ExceptionLog();
		// $log->exceptionDescription = "'$message' in $file:$line";
		// $log->exceptionFatal = true;
		// $log->log();
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

		return parent::render($request, $e);
	}
}
