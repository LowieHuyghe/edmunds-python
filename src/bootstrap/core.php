<?php

if (!defined('REAL_BASE_PATH'))
{
	define('REAL_BASE_PATH', realpath(BASE_PATH));
}
require_once __DIR__ . '/../helpers.php';
require_once REAL_BASE_PATH .'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Load the .env files
|
*/

Dotenv::load(__DIR__ . '/..');
Dotenv::load(REAL_BASE_PATH);

$missingConfig = array();
foreach (explode(',', env('CORE_CONFIG_REQUIRED')) as $line)
{
	if (!env($line))
	{
		$missingConfig[] = $line;
	}
}
if (!empty($missingConfig))
{
	throw new \Exception("The following env-values are required:\n" . implode("\n", $missingConfig));
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

$app = new \Core\Structures\Application(REAL_BASE_PATH);

$app->configure('app');
$app->make('config')->set('app.key', env('APP_KEY'));
$app->make('config')->set('app.cipher', env('APP_CIPHER'));

$app->withEloquent();

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
	App\Exceptions\Handler::class
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	App\Console\Kernel::class
);

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

$app->middleware([
	Illuminate\Cookie\Middleware\EncryptCookies::class,
	Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
	Illuminate\Session\Middleware\StartSession::class,
	Illuminate\View\Middleware\ShareErrorsFromSession::class,
	Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
]);

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

if ($providers = config('app.providers'))
{
	foreach ($providers as $provider)
	{
		$app->register($provider);
	}
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

$app->get('{route:.*}', array('uses' => '\Core\Controllers\Router@route'));
$app->post('{route:.*}', array('uses' => '\Core\Controllers\Router@route'));
$app->put('{route:.*}', array('uses' => '\Core\Controllers\Router@route'));
$app->delete('{route:.*}', array('uses' => '\Core\Controllers\Router@route'));

return $app;