<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Edmunds\Bases\Responses;

use Edmunds\Bases\Structures\BaseStructure;

/**
 * Base for Responses
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
