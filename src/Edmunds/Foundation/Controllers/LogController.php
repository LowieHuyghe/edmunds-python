<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Controllers;

use Edmunds\Analytics\Tracking\EcommerceItem;
use Edmunds\Analytics\Tracking\EcommerceLog;
use Edmunds\Analytics\Tracking\ErrorLog;
use Edmunds\Analytics\Tracking\EventLog;
use Edmunds\Analytics\Tracking\PageviewLog;
use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Validation\Validator;
use ErrorException;

/**
 * Controller responsible for logging data
 */
class LogController extends BaseController
{
	protected $outputType = \Edmunds\Http\Response::TYPE_JSON;
	/**
	 * Register the default routes for this controller
	 * @param  Application $app
	 * @param  string $prefix
	 * @param  array  $middleware
	 */
	public static function registerRoutes(&$app, $prefix ='', $middleware = array())
	{
		$app->post($prefix . 'log/{type}', '\\' . get_called_class() . '@postLog');
	}

	/**
	 * Post log data
	 */
	public function postLog($type)
	{
		switch (strtolower($type))
		{
			case 'error':
				return $this->processErrorLog();
				break;
			case 'event':
				return $this->processEventLog();
				break;
			case 'pageview':
				return $this->processPageviewLog();
				break;
			case 'ecommerce':
				return $this->processEcommerceLog();
				break;
		}

		return false;
	}

	/**
	 * Process error log
	 * @return bool
	 */
	protected function processErrorLog()
	{
		$message = $this->input->rule('message')->required()->get();
		$code = $this->input->rule('code')->fallback(0)->get();
		$file = $this->input->rule('file')->fallback('')->get();
		$line = $this->input->rule('line')->integer()->fallback(0)->get();

		// input has errors
		if ($this->input->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{
			$log = new ErrorLog();
			$log->type = 'Javascript';
			$log->exception = new ErrorException($message, $code, 1, $file, $line);
			$log->log();

			return true;
		}
	}

	/**
	 * Process event log
	 * @return bool
	 */
	protected function processEventLog()
	{
		$log = new EventLog($this->input->only(array(
			'category', 'action', 'name', 'value'
		)));

		// input has errors
		if ($log->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{
			$log->log();

			return true;
		}
	}

	/**
	 * Process pageview log
	 * @return bool
	 */
	protected function processPageviewLog()
	{
		$log = new PageviewLog($this->input->only(array(
			'url', 'referrer'
		)));

		$urlParts = parse_url($log->url);
		$log->host = $urlParts['host'];

		$path = $urlParts['path'];
		$log->path = (!$path || $path[0] != '/') ? "/$path" : $path;

		// input has errors
		if ($log->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{
			$log->log();

			return true;
		}
	}

	/**
	 * Process ecommerce log
	 * @return bool
	 */
	protected function processEcommerceLog()
	{
		$log = new EcommerceLog($this->input->only(array(
			'id', 'revenue', 'subtotal', 'shipping', 'tax', 'discount', 'previous'
		)));

		// input has errors
		if ($log->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{
			$items = array();

			foreach ($this->input->get('items') as $item)
			{
				$item = json_decode($item, true);

				$logItem = new EcommerceItem(array_only($item, array(
					'id', 'category', 'name', 'price', 'quantity'
				)));

				if (!$logItem->hasErrors())
				{
					$items[] = $logItem;
				}
			}

			$log->items = $items;
			$log->log();

			return true;
		}
	}
}