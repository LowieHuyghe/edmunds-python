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
 * A download response
 *
 * @property string $filePath
 * @property string $name
 */
class DownloadResponse extends BaseResponse
{
	/**
	 * Constructor
	 * @property string $filePath
	 * @property string $name
	 */
	public function __construct($filePath, $name = null)
	{
		parent::__construct();

		$this->filePath = $filePath;
		$this->name = $name;
	}

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		$data = $this->processData($data);

		return response()->download($this->filePath, $this->name);
	}

}
