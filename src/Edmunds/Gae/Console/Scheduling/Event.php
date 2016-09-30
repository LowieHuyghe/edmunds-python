<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Console\Scheduling;

use Edmunds\Gae\Console\Process\Process;
use Illuminate\Console\Scheduling\Event as LumenEvent;
use Illuminate\Contracts\Container\Container;

/**
 * The event for the kernel
 */
class Event extends LumenEvent
{
	/**
	 * Build the command string.
	 *
	 * @return string
	 */
	public function buildCommand()
	{
		if ($this->withoutOverlapping)
		{
			throw new \Exception('Without overlapping is not implemented yet for Google App Engine.');
		}

		return $this->command;
	}

	/**
	 * Run the command in the foreground.
	 *
	 * @param  \Illuminate\Contracts\Container\Container  $container
	 * @return void
	 */
	protected function runCommandInForeground(Container $container)
	{
		$this->callBeforeCallbacks($container);

		(new Process(
			trim($this->buildCommand(), '& '), base_path(), null, null, null
		))->run();

		$this->callAfterCallbacks($container);
	}

	/**
	 * Run the command in the background.
	 *
	 * @return void
	 */
	protected function runCommandInBackground()
	{
		(new Process(
			$this->buildCommand(), base_path(), null, null, null
		))->run();
	}
}