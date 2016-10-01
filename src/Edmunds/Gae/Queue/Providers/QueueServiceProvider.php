<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Queue\Providers;

use Edmunds\Gae\Queue\Connectors\GaeConnector;
use Edmunds\Gae\Queue\Listener;
use Edmunds\Queue\Providers\QueueServiceProvider as EdmundsQueueServiceProvider;
use Edmunds\Queue\QueueManager;


/**
 * The queue service provider
 */
class QueueServiceProvider extends EdmundsQueueServiceProvider
{
	/**
	 * Register the connectors on the queue manager.
	 *
	 * @param  \Illuminate\Queue\QueueManager  $manager
	 * @return void
	 */
	public function registerConnectors($manager)
	{
		parent::registerConnectors($manager);

		$this->registerGaeConnector($manager);
	}

	/**
	 * Register the Gae queue connector.
	 *
	 * @param  \Illuminate\Queue\QueueManager  $manager
	 * @return void
	 */
	protected function registerGaeConnector($manager)
	{
		$app = $this->app;

		$manager->addConnector('gae', function () use ($app)
		{
			return new GaeConnector($app['encrypter'], $app['request']);
		});
	}

	/**
	 * Register the queue listener.
	 *
	 * @return void
	 */
	protected function registerListener()
	{
		$this->registerListenCommand();

		$this->app->singleton('queue.listener', function ($app) {
			return new Listener($app->basePath());
		});
	}
}