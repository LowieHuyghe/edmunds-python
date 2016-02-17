<?php

if (!defined('REAL_BASE_PATH'))
{
	define('REAL_BASE_PATH', realpath(BASE_PATH));
}
if (!defined('CORE_BASE_PATH'))
{
	define('CORE_BASE_PATH', realpath(__DIR__ . '/..'));
}

require_once CORE_BASE_PATH . '/helpers.php';
require_once REAL_BASE_PATH .'/vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Load the .env files. For testing there is a seperate .env file.
|
*/

$dotEnvFile = (env('APP_ENV') != 'testing') ? '.env' : '.env.testing';
try {
    (new Dotenv\Dotenv(REAL_BASE_PATH, $dotEnvFile))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}


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

$app = new \Core\Application(REAL_BASE_PATH);


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
	config('app.exceptions.handler', Core\Exceptions\Handler::class)
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	config('app.console.kernel', Core\Console\Kernel::class)
);


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Load configuration of core and app. Futhermore check if all required
| configuration is supplied.
|
*/

$app['path.config'] = base_path('config');

$app->configure('app');
$app->configure('core');

$missingConfig = array();
foreach (config('core.config.required') as $line)
{
	if (!config($line))
	{
		$missingConfig[] = $line;
	}
}
if (!empty($missingConfig))
{
	dd(new Exception("The following config-values are required:\n" . implode("\n", $missingConfig)));
	die;
}


/*
|--------------------------------------------------------------------------
| Analytics
|--------------------------------------------------------------------------
|
| Initialize some configuration for tracking and logging.
|
*/

\Core\Registry::warehouse('newrelic');


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

//TODO check for sess and cook stuff and csrf

$app->middleware(config('app.middleware', array()));
$app->middleware(array(
	//
));

$app->routeMiddleware(config('app.routemiddleware', array()));
$app->routeMiddleware(array(
	'auth' => Core\Auth\Middleware\Authenticate::class,
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
	Core\Providers\StatefullServiceProvider::class,
	Core\Providers\AuthServiceProvider::class,
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

$app->group(['namespace' => config('app.routing.namespace')], function ($app)
{
    require REAL_BASE_PATH . '/' . config('app.routing.routes');
});


return $app;