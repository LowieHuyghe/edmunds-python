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

namespace Core\Foundation\Controllers;

use Core\Analytics\Tracking\EcommerceItem;
use Core\Analytics\Tracking\EcommerceLog;
use Core\Analytics\Tracking\ErrorLog;
use Core\Analytics\Tracking\EventLog;
use Core\Analytics\Tracking\PageviewLog;
use Core\Bases\Http\Controllers\BaseController;
use Core\Validation\Validator;
use ErrorException;

/**
 * Controller responsible for logging data
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class LogController extends BaseController
{
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
		$this->input->rule('message')->required();
		$this->input->rule('code')->fallback(0);
		$this->input->rule('file')->fallback('');
		$this->input->rule('line')->integer()->fallback(0);

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
			$log->exception = new ErrorException($this->input->get('message'), $this->input->get('code'), 1, $this->input->get('file'), $this->input->get('line'));
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
		$this->input->rule('category')->required();
		$this->input->rule('action')->required();
		$this->input->rule('name')->required();
		$this->input->rule('value')->required();

		// input has errors
		if ($this->input->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{
			$log = new EventLog();
			$log->category = $this->input->get('category');
			$log->action = $this->input->get('action');
			$log->name = $this->input->get('name');
			$log->value = $this->input->get('value');
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
		$this->input->rule('url')->required();

		// input has errors
		if ($this->input->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{

			$log = new PageviewLog();
			$log->url = $this->input->get('url');
			$log->referrer = $this->input->get('referrer');

			$urlParts = parse_url($log->url);
			$log->host = $urlParts['host'];

			$path = $urlParts['path'];
			$log->path = (!$path || $path[0] != '/') ? "/$path" : $path;

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
		$this->input->rule('id')->required();
		$this->input->rule('revenue')->numeric()->required();
		$this->input->rule('subtotal')->numeric();
		$this->input->rule('shipping')->numeric();
		$this->input->rule('tax')->numeric();
		$this->input->rule('discount')->numeric();
		$this->input->rule('items')->array_();
		$this->input->rule('previous')->date();

		// input has errors
		if ($this->input->hasErrors())
		{
			return false;
		}

		// it's ok, let's process this
		else
		{

			$log = new EcommerceLog();
			$log->id = $this->input->get('id');
			$log->revenue = $this->input->get('revenue');
			$log->subtotal = $this->input->get('subtotal');
			$log->shipping = $this->input->get('shipping');
			$log->tax = $this->input->get('tax');
			$log->discount = $this->input->get('discount');
			$log->previous = $this->input->get('previous');

			$itemValidator = new Validator();
			$itemValidator->rule('id')->required();
			$itemValidator->rule('category')->required();
			$itemValidator->rule('name')->required();
			$itemValidator->rule('price')->numeric()->required();
			$itemValidator->rule('quantity')->required();

			$items = array();
			foreach ($this->input->get('items') as $item)
			{
				$itemValidator->setInput($item);

				if (!$itemValidator->hasErrors())
				{
					$logItem = new EcommerceItem();
					$logItem->id = $this->input->get('id');
					$logItem->category = $this->input->get('category');
					$logItem->name = $this->input->get('name');
					$logItem->price = $this->input->get('price');
					$logItem->quantity = $this->input->get('quantity');

					$log->items = $items;
				}
			}

			$log->log();

			return true;
		}
	}
}