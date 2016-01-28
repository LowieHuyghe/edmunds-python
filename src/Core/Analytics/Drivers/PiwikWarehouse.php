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
use Core\Analytics\Tracking\GenericLog;
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
class PiwikWarehouse extends BaseWarehouse
{
	/**
	 * The api url
	 * @var string
	 */
	protected static $apiUrl = 'https://stats.lowiehuyghe.com/piwik.php';

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
			elseif ($log instanceof GenericLog)
			{
				$additionalAttributes = $this->processGenericLog($log);
			}
			else
			{
				throw new Exception('Piwik-warehouse does not support log: ' . get_class($log));
			}

			// process the custom values
			$customValues = ($attributes['custom'] ?? array()) + ($additionalAttributes['custom'] ?? array());
			$customValuesParam = array();
			$i = 1;
			foreach ($customValues as $key => $value)
			{
				$customValuesParam["$i"] = array($key, $value);
				++$i;
			}
			$attributes['_cvar'] = $customValuesParam ? json_encode($customValuesParam) : null;

			// assign everything
			unset($attributes['custom']);
			unset($additionalAttributes['custom']);
			$attributes = $attributes + $additionalAttributes;

			// add to requests
			$requests[] = '?' . http_build_query(array_filter($attributes));
		}

		// setup data
		$data = array(
			'requests' => $requests,
			'token_auth' => config('app.analytics.piwik.token'),
		);

		// queue that shit
		$this->queue(array(get_called_class(), 'send'), array($data));

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
		$visitorId = substr(str_replace('-', '', $log->visitorId), 0, 16);

		return array(
			'idsite' => config('app.analytics.piwik.siteid'),
			'apiv' => config('app.analytics.piwik.version'),
			'rand' => rand(0, 2000000000),
			'rec' => 1,

			'_id' => $visitorId,
			'cid' => $visitorId,
			'uid' => $log->userId,
			'lang' => $log->locale,
			'cip' => $log->ip,
			'url' => $log->url,
			'urlref' => $log->referrer,
			'h' => $log->time->format('H'),
			'm' => $log->time->format('i'),
			's' => $log->time->format('s'),
			'ua' => $log->userAgent,

			'cs' => $log->charset,
			'otherAuthTime' => $log->time->timestamp,
			'custom' => array(
				'environment' => $log->environment,
			),
		);
	}

	/**
	 * Process the PageviewLog log
	 * @param  PageviewLog $log
	 * @return array
	 */
	protected function processPageviewLog($log)
	{
		return array(
			'action_name' => $log->title,
		);
	}

	/**
	 * Process the EventLog log
	 * @param  EventLog $log
	 * @return array
	 */
	protected function processEventLog($log)
	{
		return array(
			'e_c' => $log->category,
			'e_a' => $log->action,
			'e_n' => $log->name,
			'e_v' => $log->value,
		);
	}

	/**
	 * Process the ErrorLog log
	 * @param  ErrorLog $log
	 * @return array
	 */
	protected function processErrorLog($log)
	{
		return array(
			'e_c' => 'Errors',
			'e_a' => $log->type,
			'e_n' => $log->exception->getMessage(),
			'e_v' => $log->exception->getTraceAsString(),
		);
	}

	/**
	 * Process the EcommerceLog log
	 * @param  EcommerceLog $log
	 * @return array
	 */
	protected function processEcommerceLog($log)
	{
		// prepare items
		$items = array();
		foreach ($log->items as $item)
		{
			$items[] = array(
				$item->id ?: '',
				$item->name ?: '',
				$item->category ?: '',
				$item->price ?: 0,
				$item->quantity ?: 0,
			);
		}

		return array(
			'idgoal' => 0,
			'ec_id' => $log->id,
			'ec_st' => $log->subtotal,
			'ec_sh' => $log->shipping,
			'ec_tx' => $log->tax,
			'ec_dt' => $log->discount ? 1 : 0,
			'revenue' => $log->revenue,
			'_ects' => $log->previous ? $log->previous->timestamp : null,
			'ec_items' => json_encode($items),
		);
	}

	/**
	 * Process the GenericLog log
	 * @param  GenericLog $log
	 * @return array
	 */
	protected function processGenericLog($log)
	{
		return array(
			'custom' => $log->toArray(),
		);
	}

	/**
	 * Send it all!
	 * @param  array $data
	 */
	public static function send($data)
	{
		// setup header
		$header = array('Content-type: application/x-www-form-urlencoded');

		// send request
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, static::$apiUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);

		curl_close ($ch);
	}
}
