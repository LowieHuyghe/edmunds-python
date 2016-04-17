<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Http\Responses;

use Edmunds\Bases\Responses\BaseResponse;
use Edmunds\Http\Response;

/**
 * A json response
 */
class JsonResponse extends BaseResponse
{
	/**
	 * Option to hide the base data
	 * @var boolean
	 */
	protected $hideBaseData = true;

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		$data = $this->processData($data);

		return response()->json($data);
	}

}
