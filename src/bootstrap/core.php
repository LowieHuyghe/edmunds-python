<?php

if (!defined('REAL_BASE_PATH'))
{
	define('REAL_BASE_PATH', realpath(BASE_PATH));
}
define('CORE_BASE_PATH', realpath(__DIR__ . '/..'));

require_once CORE_BASE_PATH . '/helpers.php';
require_once REAL_BASE_PATH .'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Load the .env files
|
*/

Dotenv::load(CORE_BASE_PATH);
Dotenv::load(REAL_BASE_PATH);

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

if (!$exceptionHandler = env('APP_CUSTOM_EXCEPTION_HANDLER'))
{
	$exceptionHandler = Core\Exceptions\Handler::class;
}
$app->singleton(
	Illuminate\Contracts\Debug\ExceptionHandler::class,
	$exceptionHandler
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Check if all required configuration is supplied
|
*/

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

return $app;