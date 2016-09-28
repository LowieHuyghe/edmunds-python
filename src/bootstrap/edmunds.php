<?php

try
{

	if ( ! defined('APP_BASE_PATH'))
	{
		define('APP_BASE_PATH', realpath(BASE_PATH));
	}
	if ( ! defined('EDMUNDS_BASE_PATH'))
	{
		define('EDMUNDS_BASE_PATH', realpath(__DIR__ . '/..'));
	}

	require_once EDMUNDS_BASE_PATH . '/helpers.php';
	require_once APP_BASE_PATH .'/vendor/autoload.php';


	/*
	|--------------------------------------------------------------------------
	| Configuration
	|--------------------------------------------------------------------------
	|
	| Load the .env files. For testing there is a seperate .env file.
	|
	*/

	try {
		(new Dotenv\Dotenv(APP_BASE_PATH))->load();
	} catch (Dotenv\Exception\InvalidPathException $e) {}


	/*
	|--------------------------------------------------------------------------
	| Create The Application
	|--------------------------------------------------------------------------
	|
	| Here we will load the environment and create the application instance
	| that serves as the central piece of this framework. We'll use this
	| application as an "IoC" container and router for this framework.
	|
	*/

	$app = new \Edmunds\Application(APP_BASE_PATH);


	/*
	|--------------------------------------------------------------------------
	| Configuration
	|--------------------------------------------------------------------------
	|
	| Load configuration of edmunds and app. Futhermore check if all required
	| configuration is supplied.
	|
	*/

	$app['path.config'] = base_path('config');

	$app->configure('app');
	$app->configure('edmunds');

	$missingConfig = array();
	foreach (config('edmunds.config.required') as $line)
	{
		if ( ! config($line))
		{
			$missingConfig[] = $line;
		}
	}
	if ( ! empty($missingConfig))
	{
		throw new Exception("The following config-values are required:\n" . implode("\n", $missingConfig));
	}


	/*
	|--------------------------------------------------------------------------
	| Register Container Bindings
	|--------------------------------------------------------------------------
	|
	| Now we will register a few bindings in the service container. We will
	| register the exception handler and the console kernel. You may add
	| your own bindings here if you like or you can make another file.
	|
	*/

	$app->singleton(
		Illuminate\Contracts\Debug\ExceptionHandler::class,
		config('app.exceptions.handler', Edmunds\Foundation\Exceptions\Handler::class)
	);

	$app->singleton(
		Illuminate\Contracts\Console\Kernel::class,
		config('app.console.kernel', Edmunds\Console\Kernel::class)
	);


	/*
	|--------------------------------------------------------------------------
	| Analytics
	|--------------------------------------------------------------------------
	|
	| Initialize some configuration for tracking and logging.
	|
	*/

	\Edmunds\Registry::warehouse('newrelic');


	/*
	|--------------------------------------------------------------------------
	| Eloquent
	|--------------------------------------------------------------------------
	|
	| Enable eloquent for models in the application.
	|
	*/

	$app->withEloquent();


	/*
	|--------------------------------------------------------------------------
	| Register Middleware
	|--------------------------------------------------------------------------
	|
	| Next, we will register the middleware with the application. These can
	| be global middleware that run before and after each request into a
	| route or middleware that'll be assigned to some specific routes.
	|
	*/

	$app
		->middleware(config('app.middleware', array()))
		->middleware(array(
			// \Edmunds\Foundation\Http\Middleware\VerifyCsrfToken::class,
		))
		->routeMiddleware(config('app.routemiddleware', array()))
		->routeMiddleware(array(
			// 'auth' => Edmunds\Auth\Middleware\AuthMiddleware::class,
			// 'rights' => Edmunds\Auth\Middleware\RightsMiddleware::class,
			// 'roles' => Edmunds\Auth\Middleware\RolesMiddleware::class,
			// 'guest' => Edmunds\Auth\Middleware\RedirectIfAuthenticated::class,
		));


	/*
	|--------------------------------------------------------------------------
	| Register Service Providers
	|--------------------------------------------------------------------------
	|
	| Here we will register all of the application's service providers which
	| are used to bind services into the container. Service providers are
	| totally optional, so you are not required to uncomment this line.
	|
	*/

	$providers = array(
		Edmunds\Foundation\Providers\StatefullServiceProvider::class,
		Edmunds\Auth\Providers\AuthServiceProvider::class,
	);
	$providers = array_merge($providers, config('app.providers', array()));

	foreach ($providers as $provider)
	{
		$app->register($provider);
	}


	/*
	|--------------------------------------------------------------------------
	| Load The Application Routes
	|--------------------------------------------------------------------------
	|
	| Next we will include the routes file so that they can all be added to
	| the application. This will provide all of the URLs the application
	| can respond to, as well as the controllers that may handle them.
	|
	*/

	require APP_BASE_PATH . '/' . config('app.routing.routes');

	return $app;

}
catch (Exception $e)
{

	// when application is not set, render exception-page
	if ( ! isset($app))
	{
		$exceptionHandler = new Edmunds\Foundation\Exceptions\Handler();
		$request = new Illuminate\Http\Request();

		$exceptionHandler->render($request, $e)->sendContent();
	}

	throw $e;

}
