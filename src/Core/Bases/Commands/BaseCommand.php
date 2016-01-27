<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Core\Bases\Commands;

use Core\Registry;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command base to extend from
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class BaseCommand extends Command
{
	/**
	 * Log automatically to pm
	 * @var boolean
	 */
	protected $logPm = false;

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

			$this->logPm ? Registry::pm()->info($title, 'Succesful') : null;

			return $response;
		}
		catch (\Exception $e)
		{
			$this->logPm ? Registry::pm()->info($title, 'Failed') : null;

			throw $e;
		}
		catch (\Throwable $e)
		{
			$this->logPm ? Registry::pm()->info($title, 'Failed') : null;

			throw $e;
		}

	}

}
