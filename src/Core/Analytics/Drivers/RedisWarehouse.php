<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Analytics\Drivers;

use Core\Analytics\Tracking\EcommerceLog;
use Core\Analytics\Tracking\ErrorLog;
use Core\Analytics\Tracking\EventLog;
use Core\Analytics\Tracking\GenericLog;
use Core\Analytics\Tracking\PageviewLog;
use Core\Bases\Analytics\BaseWarehouse;
use Core\Bases\Analytics\Tracking\BaseLog;
use Core\Events\BroadcastEvent;
use Core\Queue\Queue;
use Exception;

/**
 * The redis warehouse driver
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class RedisWarehouse extends BaseWarehouse
{
	/**
	 * Flush all the saved up logs
	 */
	public function flush()
	{
		// process logs
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
				throw new Exception('Redis-warehouse does not support log: ' . get_class($log));
			}

			// assign everything
			$attributes = $attributes + $additionalAttributes;

			// broadcast it
			(new BroadcastEvent(array('log'), $attributes, Queue::QUEUE_LOG))->broadcast();
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
			'time' => $log->time->timestamp,
			'request' => array(
				'ip' => $log->ip,
				'url' => $log->url,
				'referrer' => $log->referrer,
			),
			'visitor' => array(
				'id' => $log->visitorId,
				'locale' => $log->locale,
				'time' => array(
					'h' => $log->time->format('H'),
					'm' => $log->time->format('i'),
					's' => $log->time->format('s'),
				),
				'ua' => $log->userAgent,
			),
			'user' => array(
				'id' => $log->userId,
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
			'type' => 'pageview',
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
			'type' => 'event',

			'event' => array(
				'category' => $log->category,
				'action' => $log->action,
				'name' => $log->name,
				'value' => $log->value,
			),
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
			'type' => 'error',

			'error' => array(
				'type' => $log->type,
				'file' => $log->exception->getFile(),
				'line' => $log->exception->getLine(),
				'code' => $log->exception->getCode(),
				'message' => $log->exception->getMessage(),
				'trace' => $log->exception->getTraceAsString(),
			),
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
				'id' => $item->id,
				'name' => $item->name,
				'category' => $item->category,
				'price' => $item->price,
				'quantity' => $item->quantity,
			);
		}

		return array(
			'type' => 'ecommerce',

			'ecommerce' => array(
				'id' => $log->id,
				'subtotal' => $log->subtotal,
				'shipping' => $log->shipping,
				'tax' => $log->tax,
				'discount' => $log->discount,
				'revenue' => $log->revenue,
				'previous' => $log->previous ? $log->previous->timestamp : null,
				'items' => $items,
			),
		);
	}
}
