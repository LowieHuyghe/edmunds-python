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

/**
 * A content response
 *
 * @property mixed $content
 */
class ContentResponse extends BaseResponse
{
	/**
	 * Constructor
	 * @param mixed $content
	 */
	public function __construct($content)
	{
		parent::__construct();

		$this->content = $content;
	}

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		$data = $this->processData($data);

		return response($this->content);
	}

}
