<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Analytics\Drivers;

use Edmunds\Analytics\Tracking\ErrorLog;
use Edmunds\Analytics\Tracking\PageviewLog;
use Edmunds\Bases\Analytics\BaseWarehouse;
use Exception;

/**
 * The piwik warehouse driver
 *
 * @property bool $loaded
 * @property string $license
 * @property string $appName
 */
class NewrelicWarehouse extends BaseWarehouse
{
	/**
	 * Constructor
	 * @param string $driver
	 */
	public function __construct($driver)
	{
		parent::__construct($driver);

		$this->appName = app()->getName();
		$this->license = config('app.analytics.newrelic.license') ?: ini_get('newrelic.license');

		$this->loaded = $this->license && extension_loaded('newrelic');

		// set the app name
		if ($this->loaded)
		{
			newrelic_set_appname($this->appName, $this->license);
		}
	}

	/**
	 * Set the name of the transaction
	 * (This is a not so beautiful fix)
	 * @param string $name
	 */
	public function setTransactionName($name)
	{
		if ($this->loaded)
		{
			newrelic_name_transaction($name);
		}
	}

	/**
	 * Actually log something
	 * @param  BaseLog $log
	 * @return void
	 */
	protected function doLog($log)
	{
		// check if loaded
		if ( ! $this->loaded)
		{
			return;
		}

		// get the log attributes
		$logAttributes = $log->getAttributes();

		// process the log
		if ($log instanceof ErrorLog)
		{
			$this->processErrorLog($log);
		}
		else
		{
			throw new Exception('Newrelic-warehouse does not support log: ' . get_class($log));
		}
	}

	/**
	 * Process the ErrorLog log
	 * @param  ErrorLog $log
	 */
	protected function processErrorLog($log)
	{
		newrelic_notice_error($log->exception->getMessage(), $log->exception);
	}
}
