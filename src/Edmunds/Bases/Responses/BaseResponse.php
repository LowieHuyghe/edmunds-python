<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Responses;

use Edmunds\Bases\Structures\BaseStructure;

/**
 * Base for Responses
 */
class BaseResponse extends BaseStructure
{
	/**
	 * Option to hide the base data
	 * @var boolean
	 */
	protected $hideBaseData = false;

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		$data = $this->processData($data);

		return response()->make();
	}

	/**
	 * Process the data
	 * @param  array $data
	 * @return array
	 */
	protected function processData($data)
	{
		// hide base data
		if ($this->hideBaseData)
		{
			$processData = array();

			foreach ($data as $key => $value)
			{
				if ($key[0] != '_')
				{
					$processData[$key] = $value;
				}
			}

			return $processData;
		}

		// return full data
		return $data;
	}
}
