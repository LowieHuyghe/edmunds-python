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

namespace Core\Bases\Analytics\Tracking;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for logs
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseLog extends BaseLogValue
{
	/** @var string The api-url*/
	protected static $apiUrl;

	/**
	 * Report the log
	 * @throws \Exception
	 */
	public function report()
	{
		//
	}

	/**
	 * Send the data
	 * @param array $header
	 * @param int $count
	 * @param array $data
	 * @param double $timeReported
	 */
	public static function send($header, $count, $data, $timeReported)
	{
		//Send request
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, static::$apiUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, $count);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);

		curl_close ($ch);
	}

}
