<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Console\Scheduling;

use Edmunds\Gae\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as LumenSchedule;

/**
 * The scheduling for the kernel
 */
class Schedule extends LumenSchedule
{
	/**
	 * Add a new Artisan command event to the schedule.
	 *
	 * @param  string  $command
	 * @param  array  $parameters
	 * @return \Illuminate\Console\Scheduling\Event
	 */
	public function command($command, array $parameters = [])
	{
		if (defined('ARTISAN_BINARY'))
		{
			$artisan = ARTISAN_BINARY;
		}
		else
		{
			$artisan = 'artisan';
		}

		return $this->exec("{$artisan} ${command}", $parameters);
	}

	/**
	 * Add a new command event to the schedule.
	 *
	 * @param  string  $command
	 * @param  array  $parameters
	 * @return \Illuminate\Console\Scheduling\Event
	 */
	public function exec($command, array $parameters = [])
	{
		if (count($parameters))
		{
			$command .= ' ' . $this->compileParameters($parameters);
		}

		$this->events[] = $event = new Event($command);

		return $event;
	}

	/**
	 * Compile parameters for a command.
	 *
	 * @param  array  $parameters
	 * @return string
	 */
	protected function compileParameters(array $parameters)
	{
		return collect($parameters)->map(function ($value, $key)
		{
			return is_numeric($key) ? $value : $key . '=' . $value;
		})->implode(' ');
	}
}