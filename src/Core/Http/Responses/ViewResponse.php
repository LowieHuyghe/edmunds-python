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

namespace Core\Http\Responses;

use Core\Bases\Responses\BaseResponse;

/**
 * A view response
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ViewResponse extends BaseResponse
{
	/**
	 * The views
	 * @var array
	 */
	private $views;

	/**
	 * Constructor
	 * @param array $views
	 * @param array $data
	 */
	public function __construct($views = array())
	{
		parent::__construct();

		$this->views = $views;
	}

	/**
	 * Add a view
	 * @param string $key
	 * @param string $view
	 */
	public function addView($key = null, $view = null)
	{
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->addView($key, $view);
			}
		}
		else
		{
			if (is_null($key))
			{
				$key = '__';
			}

			$this->views[$key] = $view;
		}
	}

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		return response()->make($this->getViewResponse($data));
	}

	/**
	 * Get the view response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	protected function getViewResponse($data = array())
	{
		ksort($this->views);

		$response = null;
		foreach ($this->views as $key => $view)
		{
			if (is_null($response))
			{
				$response = view($view, $data);
			}
			else
			{
				$response = $response->nest($key, $view, $data);
			}
		}

		return $response;
	}

	/**
	 * Get rendered response
	 * @param array $data
	 * @return Illuminate\Http\Response
	 */
	public function getRendered($data = array())
	{
		return $this->getViewResponse($data)->render();
	}

}
