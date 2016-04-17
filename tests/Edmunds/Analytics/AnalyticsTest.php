<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace EdmundsTest\Analytics;

use Edmunds\Analytics\Tracking\EcommerceItem;
use Edmunds\Analytics\Tracking\EcommerceLog;
use Edmunds\Analytics\Tracking\ErrorLog;
use Edmunds\Analytics\Tracking\EventLog;
use Edmunds\Analytics\Tracking\PageviewLog;
use Edmunds\Bases\Tests\BaseTest;
use Edmunds\Http\Client\Visitor;
use Edmunds\Localization\Format\DateTime;
use Edmunds\Registry;
use Exception;

/**
 * Testing Analytics-class
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class AnalyticsTest extends BaseTest
{
	/**
	 * Test Pageview Log
	 */
	public function testPageviewLog()
	{
		$log = new PageviewLog();
		$log->title = 'PageviewLogTitle';

		$log->log();
		Registry::warehouse()->flush();

		$this->assertTrue(true);
	}

	/**
	 * Test Error Log
	 */
	public function testErrorLog()
	{
		$log = new ErrorLog();
		$log->type = 'ErrorLogTest';
		$log->exception = new Exception('ErrorLogException');

		$log->log();
		Registry::warehouse()->flush();

		$this->assertTrue(true);
	}

	/**
	 * Test Event Log
	 */
	public function testEventLog()
	{
		$log = new EventLog();
		$log->category = 'EventLogCategory';
		$log->action = 'EventLogAction';
		$log->name = 'EventLogName';
		$log->value = 'EventLogValue';

		$log->log();
		Registry::warehouse()->flush();

		$this->assertTrue(true);
	}

	/**
	 * Test Ecommerce Log
	 */
	public function testEcommerceLog()
	{
		$items = array();
		for ($i=1; $i <= 2; $i++)
		{
			$item = new EcommerceItem();
			$item->id = "777-$i";
			$item->name = "EcommerceItemName$i";
			$item->category = "EcommerceItemCategory$i";
			$item->price = 7.1 * $i;
			$item->quantity = $i;

			$items[] = $item;
		}

		$log = new EcommerceLog();
		$log->id = 'TestOrder' . time();
		$log->category = 'EcommerceLogId';
		$log->subtotal = 35.5;
		$log->shipping = 5.3;
		$log->tax = 8.57;
		$log->discount = 4.08;
		$log->revenue = 36.72;
		$log->currencyCode = Visitor::getInstance()->localization->currency;
		$log->items = $items;
		$log->previous = (new DateTime())->addDays(-3);

		$log->log();
		Registry::warehouse()->flush();

		$this->assertTrue(true);
	}
}
