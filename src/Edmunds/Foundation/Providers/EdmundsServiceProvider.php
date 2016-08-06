<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Providers;

use Edmunds\Bases\Providers\BaseServiceProvider;
use Edmunds\Http\Client\Input;
use Edmunds\Http\Client\Visitor;
use Edmunds\Http\Request;
use Edmunds\Http\Response;


/**
 * The Edmunds service provider
 */
class EdmundsServiceProvider extends BaseServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRequest();
		$this->registerResponse();
		$this->registerVisitor();
		$this->registerInput();
	}

	/**
	 * Register Request
	 * @return void
	 */
	protected function registerRequest()
	{
		$this->app->singleton('edmunds.request', function ($app)
		{
			return new Request();
		});
	}

	/**
	 * Register Response
	 * @return void
	 */
	protected function registerResponse()
	{
		$this->app->singleton('edmunds.response', function ($app)
		{
			return new Response($app['edmunds.request']);
		});
	}

	/**
	 * Register Visitor
	 * @return void
	 */
	protected function registerVisitor()
	{
		$this->app->singleton('edmunds.visitor', function ($app)
		{
			return new Visitor($app['edmunds.request'], $app['edmunds.response']);
		});
	}

	/**
	 * Register Input
	 * @return void
	 */
	protected function registerInput()
	{
		$this->app->singleton('edmunds.input', function ($app)
		{
			return new Input($app['edmunds.request']);
		});
	}

}