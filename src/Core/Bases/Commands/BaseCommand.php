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

use Core\Registry\Registry;
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
		try
		{
			$title = 'Command Execute: ' . $this->name;
			$body = 'Succesful';

			$response = parent::execute($input, $output);

			$this->logPm ? Registry::pm()->info($title, $body) : null;

			return $response;
		}
		catch (\Exception $e)
		{
			$body = 'Failed';

			$this->logPm ? Registry::pm()->info($title, $body) : null;

			throw $e;
		}
	}

}
