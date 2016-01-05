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

namespace Core\Bases\Analytics;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for reports
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseReport extends BaseStructure
{
	/**
	 * The mapping of the parameters for the call
	 * @var array
	 */
	protected $parameterMapping = array();

	/**
	 * The api-url
	 * @var string
	 */
	protected static $apiUrl;

	/**
	 * Enable or disable timestamps by default
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Report the log
	 * @throws \Exception
	 */
	public function report()
	{
//		if ($this->hasErrors())
//		{
//			throw new \Exception('This report has errors and can not be sent: ' . json_encode($this->getErrors()->getMessageBag()->toArray()));
//		}

		//Set up all the variables and the right values
		$data = array();
		foreach ($this->attributes as $parameter => $value)
		{
			if (!isset($this->parameterMapping[$parameter]))
			{
				//throw new \Exception("There is no mapping for the parameter: $parameter");
				continue;
			}

			//Bool needs to be 1/0
			if (is_bool($value))
			{
				$value = $value ? 1 : 0;
			}

			if (is_array($value))
			{
				foreach ($value as $customValue)
				{
					//Some parameter-names need to be filled in
					$parameterName = $this->parameterMapping[$parameter];

					for ($i=0 ; $i < count($customValue)-1 ; ++$i)
					{
						$parameterName = str_replace('{' . $i . '}', $customValue[$i], $parameterName);
					}
					$customValue = last($customValue);

					//Add query-item
					$data[$parameterName] = $customValue;
				}
			}
			else
			{
				//Some parameter-names need to be filled in
				$parameterName = $this->parameterMapping[$parameter];

				//Add query-item
				$data[$parameterName] = $value;
			}
		}

		//Setup header
		$header = array('Content-type: application/x-www-form-urlencoded');
		if ($this->userAgentOverride)
		{
			$header[] = 'User-Agent: ' . $this->userAgentOverride;
		}

		Registry::queue()->dispatch(array(get_called_class(), 'send'), array(
			$header, $data, microtime(true),
		), Queue::QUEUE_LOG);
	}

	/**
	 * Send the data
	 * @param string $apiUrl
	 * @param array $header
	 * @param array $data
	 * @param int $timeReported
	 */
	public static function send($apiUrl, $header, $data, $timeReported)
	{
		//Send request
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
