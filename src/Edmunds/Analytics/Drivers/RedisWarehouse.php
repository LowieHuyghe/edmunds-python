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
use Edmunds\Events\BroadcastEvent;
use Edmunds\Queue\Queue;
use Exception;

/**
 * The redis warehouse driver
 */
class RedisWarehouse extends BaseWarehouse
{
	/**
	 * Actually log something
	 * @param  BaseLog $log
	 * @return void
	 */
	protected function doLog($log)
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
