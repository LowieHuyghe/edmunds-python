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
 * A view response
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
		$data = $this->processData($data);

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
