<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Helpers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\JsonResponse;

/**
 * The helper responsible for the response
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ResponseHelper extends BaseHelper
{
	/**
	 * Instance of the response-helper
	 * @var ResponseHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return ResponseHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new ResponseHelper();
		}

		return self::$instance;
	}

	/**
	 * The status-code for the response
	 * @var int
	 */
	private $statusCode = 200;

	/**
	 * Assigned data for response
	 * @var array
	 */
	private $assignedData = array();

	/**
	 * Assigned cookies for response
	 * @var array
	 */
	private $assignedCookies = array();

	/**
	 * Assigned headers for response
	 * @var array
	 */
	private $assignedHeaders = array();

	/**
	 * Assign data to response
	 * @param string $key
	 * @param mixed $value
	 */
	public function assignData($key, $value)
	{
		$this->assignedData[$key] = $value;
	}

	/**
	 * Assign cookies to response
	 * @param string $key
	 * @param mixed $value
	 */
	public function assignCookie($key, $value)
	{
		$this->assignedCookies[$key] = $value;
	}

	/**
	 * Assign headers to response
	 * @param string $key
	 * @param mixed $value
	 */
	public function assignHeaders($key, $value)
	{
		$this->assignedHeaders[$key] = $value;
	}

	/**
	 * Set the response as success : 200
	 */
	public function setSuccess()
	{
		$this->statusCode = 200;
	}

	/**
	 * Set the response as failed : 500
	 */
	public function setFailed()
	{
		$this->statusCode = 500;
	}

	/**
	 * Fetch the build response
	 * @param mixed $content
	 * @return ResponseFactory
	 */
	private function getResponse($content = '')
	{
		$response = response($content, $this->statusCode);

		//Assign cookies
		foreach ($this->assignedCookies as $key => $value)
		{
			$response->withCookie($key, $value);
		}

		//Assign headers
		foreach ($this->assignedHeaders as $key => $value)
		{
			$response->header($key, $value);
		}

		return $response;
	}

	/**
	 * Return content
	 * @param mixed $content
	 * @return ResponseFactory
	 */
	public function returnContent($content)
	{
		return $this->getResponse($content);
	}

	/**
	 * Return view
	 * @param string $view
	 * @return Response
	 */
	public function returnView($view)
	{
		return $this->getResponse()->view($view, $this->assignedData);
	}

	/**
	 * Return json-data
	 * @return JsonResponse
	 */
	public function returnJson()
	{
		return $this->getResponse()->json($this->assignedData);
	}

	/**
	 * Return a download
	 * @param string $file
	 * @param string $name
	 * @return BinaryFileResponse
	 */
	public function returnDownload($file, $name)
	{
		return $this->getResponse()->download($file, $name);
	}

	/**
	 * Redirect to the specified url
	 * @param string $url
	 * @param bool|array $input
	 * @return RedirectResponse
	 */
	public function returnRedirect($url, $input = false)
	{
		$redirect = redirect($url);

		if ($input === true)
		{
			$redirect = $redirect->withInput();
		}
		elseif (is_array($input))
		{
			$redirect = $redirect->withInput($input);
		}

		return $redirect;
	}
}
