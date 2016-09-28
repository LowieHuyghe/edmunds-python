<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Console\Process\Concerns;

use Illuminate\Console\Application as Artisan;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Exception;
use Throwable;

/**
 * Trait for artisan process
 */
trait ArtisanProcess
{
	/**
	 * Handle an artisan command
	 * @param  string $command
	 * @return int|null
	 */
	protected function handleArtisanCommand($command)
	{
		$input = new ArrayInput(array(
			$command,
		));
		$output = new NullOutput();

		return app('kernel')->handle($input, $output);
	}
}