<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Commands;

use Edmunds\Registry;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command base to extend from
 */
class BaseCommand extends Command
{
	/**
	 * Log automatically to pm
	 * @var boolean
	 */
	protected $logChannel = false;

	/**
	 * Execute the console command.
	 * @param  \Symfony\Component\Console\Input\InputInterface  $input
	 * @param  \Symfony\Component\Console\Output\OutputInterface  $output
	 * @return mixed
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$title = 'Command Execute: ' . $this->name;

		try
		{
			$response = parent::execute($input, $output);

			$this->logChannel ? Registry::channel()->info($title, 'Succesful') : null;

			return $response;
		}
		catch (\Exception $e)
		{
			$this->logChannel ? Registry::channel()->info($title, 'Failed') : null;

			throw $e;
		}
		catch (\Throwable $e)
		{
			$this->logChannel ? Registry::channel()->info($title, 'Failed') : null;

			throw $e;
		}

	}

}
