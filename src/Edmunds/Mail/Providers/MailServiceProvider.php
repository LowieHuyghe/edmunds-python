<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Mail\Providers;

use Edmunds\Mail\TransportManager;
use Illuminate\Mail\MailServiceProvider as BaseMailServiceProvider;


/**
 * The mail service provider
 */
class MailServiceProvider extends BaseMailServiceProvider
{
	/**
	 * Register the Swift Transport instance.
	 *
	 * @return void
	 */
	protected function registerSwiftTransport()
	{
		$this->app['swift.transport'] = $this->app->share(function ($app) {
			return new TransportManager($app);
		});
	}
}