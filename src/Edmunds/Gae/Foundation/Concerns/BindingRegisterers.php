<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Foundation\Concerns;

/**
 * The RegistersExceptionHandlers concern
 */
trait BindingRegisterers
{
	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerQueueBindings()
	{
		$this->singleton('queue', function ()
		{
			return $this->loadComponent('queue', 'Edmunds\Gae\Queue\Providers\QueueServiceProvider', 'queue');
		});
		$this->singleton('queue.connection', function ()
		{
			return $this->loadComponent('queue', 'Edmunds\Gae\Queue\Providers\QueueServiceProvider', 'queue.connection');
		});
		$this->singleton('queue.worker', function ()
		{
			return $this->loadComponent('queue', 'Edmunds\Gae\Queue\Providers\QueueServiceProvider', 'queue.worker');
		});
	}
}