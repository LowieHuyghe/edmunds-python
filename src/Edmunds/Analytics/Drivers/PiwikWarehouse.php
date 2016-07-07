<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Analytics\Drivers;

use Edmunds\Analytics\Tracking\EcommerceLog;
use Edmunds\Analytics\Tracking\ErrorLog;
use Edmunds\Analytics\Tracking\EventLog;
use Edmunds\Analytics\Tracking\GenericLog;
use Edmunds\Analytics\Tracking\PageviewLog;
use Edmunds\Bases\Analytics\BaseWarehouse;
use Edmunds\Bases\Analytics\Tracking\BaseLog;
use Exception;

/**
 * The piwik warehouse driver
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
			else
			{
				throw new Exception('Piwik-warehouse does not support log: ' . get_class($log));
			}

			// process the custom values
			$attributes['cvar'] = $this->processCustomVars('cvar', $attributes, $additionalAttributes);
			$attributes['_cvar'] = $this->processCustomVars('_cvar', $attributes, $additionalAttributes);
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
	 * Process the custom variables
	 * @param  string $name
	 * @param  array &$attributes
	 * @param  array &$additionalAttributes
	 * @return array
	 */
	protected function processCustomVars($name, &$attributes, &$additionalAttributes)
	{
		$customValues = (isset($attributes[$name]) ? $attributes[$name] : array()) + (isset($additionalAttributes[$name]) ? $additionalAttributes[$name] : array());
		$customValuesParam = array();

		$i = 1;
		foreach ($customValues as $key => $value)
		{
			$customValuesParam["$i"] = array($key, $value);
			++$i;
		}

		unset($attributes[$name]);
		unset($additionalAttributes[$name]);
	}

	/**
	 * Process the BaseLog log
	 * @param  BaseLog $log
	 * @return array
	 */
	protected function processBaseLog($log)
	{
		$visitorId = substr(str_replace('-', '', $log->visitorId), 0, 16);

		$assigns = array(
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

			'otherAuthTime' => $log->time->timestamp,
		);

		return $assigns + $this->getCustomAssignments($log, 'dimensions', 'dimension{0}');
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