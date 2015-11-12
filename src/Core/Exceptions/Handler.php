<?php

namespace Core\Exceptions;

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
        HttpException::class,
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
        return parent::report($e);
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
        if (!env('APP_DEBUG'))
        {
            if ($e instanceof UnauthorizedHttpException)
            {
                $response = Response::current();

                $response->assignHeader($e->getHeaders());
                $response->assignView(null, 'errors.401');
                $response->assign('message', $e->getMessage());
                $response->setType(Response::TYPE_401);

                return $response->getResponse();
            }
            elseif ($e instanceof AccessDeniedHttpException)
            {
                $response = Response::current();

                $response->assignHeader($e->getHeaders());
                $response->assignView(null, 'errors.403');
                $response->assign('message', $e->getMessage());
                $response->setType(Response::TYPE_403);

                return $response->getResponse();
            }
            elseif ($e instanceof NotFoundHttpException)
            {
                $response = Response::current();

                $response->assignHeader($e->getHeaders());
                $response->assignView(null, 'errors.404');
                $response->assign('message', $e->getMessage());
                $response->setType(Response::TYPE_404);

                return $response->getResponse();
            }
            elseif ($e instanceof ServiceUnavailableHttpException)
            {
                $response = Response::current();

                $response->assignHeader($e->getHeaders());
                $response->assignView(null, 'errors.503');
                $response->assign('message', $e->getMessage());
                $response->assign('maintenance', app()->isDownForMaintenance());
                $response->setType(Response::TYPE_503);

                return $response->getResponse();
            }
        }

        return parent::render($request, $e);
    }
}
