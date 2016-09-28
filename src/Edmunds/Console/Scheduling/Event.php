<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Console\Scheduling;

use Edmunds\Console\Process\Process;
use Illuminate\Console\Scheduling\Event as LumenEvent;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Process\PhpExecutableFinder;

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
		// Google App Engine
		if (app()->isGae())
		{
			if ($this->withoutOverlapping)
			{
				throw new \Exception('Without overlapping is not implemented yet for Google App Engine.');
			}

			return $this->command;
		}

		return parent::buildCommand();
	}

	/**
	 * Run the command in the foreground.
	 *
	 * @param  \Illuminate\Contracts\Container\Container  $container
	 * @return void
	 */
	protected function runCommandInForeground(Container $container)
	{
		// Google App Engine
		if (app()->isGae())
		{
			$this->callBeforeCallbacks($container);

			(new Process(
				trim($this->buildCommand(), '& '), base_path(), null, null, null
			))->run();

			$this->callAfterCallbacks($container);
		}
		else
		{
			parent::runCommandInForeground($container);
		}
	}

	/**
	 * Run the command in the background.
	 *
	 * @return void
	 */
	protected function runCommandInBackground()
	{
		// Google App Engine
		if (app()->isGae())
		{
			(new Process(
				$this->buildCommand(), base_path(), null, null, null
			))->run();
		}
		else
		{
			parent::runCommandInBackground();
		}
	}
}