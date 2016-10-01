<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Queue;

use Illuminate\Queue\Listener as LumenListener;


/**
 * The listener class
 */
class Listener extends LumenListener
{
	/**
	 * Build the environment specific worker command.
	 *
	 * @return string
	 */
	protected function buildWorkerCommand()
	{
		if (defined('ARTISAN_BINARY'))
		{
			$artisan = ARTISAN_BINARY;
		}
		else
		{
			$artisan = 'artisan';
		}

		$command = 'queue:work %s --queue=%s --delay=%s --memory=%s --sleep=%s --tries=%s';

		return "{$artisan} ${command}";
	}
}