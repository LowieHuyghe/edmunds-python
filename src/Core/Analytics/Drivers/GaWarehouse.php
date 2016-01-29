<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Analytics\Drivers;

use Core\Analytics\Tracking\EcommerceLog;
use Core\Analytics\Tracking\ErrorLog;
use Core\Analytics\Tracking\EventLog;
use Core\Analytics\Tracking\PageviewLog;
use Core\Bases\Analytics\BaseWarehouse;
use Core\Bases\Analytics\Tracking\BaseLog;
use Exception;

/**
 * The piwik warehouse driver
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class GaWarehouse extends BaseWarehouse
{
	/**
	 * The api-url
	 * @var string
	 */
	protected static $apiUrl = 'https://ssl.google-analytics.com/collect';

	/**
	 * Flush all the saved up logs
	 */
	public function flush()
	{
		// fetch the requests
		$requests = array();
		foreach ($this->logs as $log)
		{
			// fetch the base stuff
			$attributes = $this->processBaseLog($log);

			// process event specific
			if ($log instanceof PageviewLog)
			{
				$additionalAttributes = $this->processPageviewLog($log);
			}
			elseif ($log instanceof EventLog)
			{
				$additionalAttributes = $this->processEventLog($log);
			}
			elseif ($log instanceof ErrorLog)
			{
				$additionalAttributes = $this->processErrorLog($log);
			}
			elseif ($log instanceof EcommerceLog)
			{
				$additionalAttributes = $this->processEcommerceLog($log);
			}
			else
			{
				throw new Exception('Ga-warehouse does not support log: ' . get_class($log));
			}

			// log each one
			foreach ($additionalAttributes as $additionalAttribute)
			{
				// queue it
				$this->queue(array(get_called_class(), 'send'), array($attributes + $additionalAttribute, microtime(true)));
			}
		}

		// empty the logs
		parent::flush();
	}

	/**
	 * Process the BaseLog log
	 * @param  BaseLog $log
	 * @return array
	 */
	protected function processBaseLog($log)
	{
		return array(
			'tid' => config('app.analytics.ga.trackignid'),
			'v' => config('app.analytics.ga.version'),
			'z' => rand(0, 2000000000),

			'cid' => $log->visitorId,
			'uid' => $log->userId,
			'ul' => $log->locale,
			'uip' => $log->ip,
			'dl' => $log->url,
			'dh' => $log->host,
			'dp' => $log->path,
			'dr' => $log->referrer,
			'ua' => $log->userAgent,

			'cdenvironment' => $log->environment,
		);
	}

	/**
	 * Process the PageviewLog log
	 * @param  PageviewLog $log
	 * @return array
	 */
	protected function processPageviewLog($log)
	{
		return array(array(
			't' => 'pageview',

			'dt' => $log->title,
		));
	}

	/**
	 * Process the EventLog log
	 * @param  EventLog $log
	 * @return array
	 */
	protected function processEventLog($log)
	{
		return array(array(
			't' => 'event',

			'ec' => $log->category,
			'ea' => $log->action,
			'el' => $log->name,
			'ev' => $log->value,
		));
	}

	/**
	 * Process the ErrorLog log
	 * @param  ErrorLog $log
	 * @return array
	 */
	protected function processErrorLog($log)
	{
		return array(array(
			't' => 'exception',

			'exd' => $log->exception->getMessage(),
			//'exf' => true,
		));
	}

	/**
	 * Process the EcommerceLog log
	 * @param  EcommerceLog $log
	 * @return array
	 */
	protected function processEcommerceLog($log)
	{
		$logs = array(array(
			't' => 'transaction',

			'ti' => $log->id,
			'ta' => $log->category,
			'ts' => $log->shipping,
			'tt' => $log->tax,
			'tr' => $log->revenue,
			'cu' => $log->currencyCode,
		));

		// add items
		$items = array();
		foreach ($log->items as $item)
		{
			$logs[] = array(
				't' => 'item',

				'ic' => $item->id,
				'in' => $item->name,
				'iv' => $item->category,
				'ip' => $item->price,
				'iq' => $item->quantity,
				'cu' => $log->currencyCode,
			);
		}

		return $logs;
	}

	/**
	 * Send it all!
	 * @param  array $data
	 * @param  float $timeReported
	 */
	public static function send($data, $timeReported)
	{
		// setup header
		$header = array('Content-type: application/x-www-form-urlencoded');

		//Add queue time
		$queueTime = round((microtime(true) - $timeReported) * 1000);
		$data['qt'] = $queueTime;

		// send request
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, static::$apiUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);

		curl_close ($ch);
	}
}
