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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
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
	 * Statuscode of response
	 * @var int
	 */
	private $statusCode = 200;

	/**
	 * Assigned data for response
	 * @var array
	 */
	private $assignedData = array();

	/**
	 * Assigned data for view response
	 * @var array
	 */
	private $assignedViewData = array();

	/**
	 * Assigned cookies for response
	 * @var Cookie[]
	 */
	private $assignedCookies = array();

	/**
	 * Assigned headers for response
	 * @var array
	 */
	private $assignedHeaders = array();

	/**
	 * Set the response as success (default)
	 */
	public function setSuccess()
	{
		$this->statusCode = 200;
	}

	/**
	 * Set the response as failed
	 */
	public function setFailed()
	{
		$this->statusCode = 500;
	}

	/**
	 * Assign data to response
	 * @param string $key
	 * @param mixed $value
	 * @param bool $general
	 */
	public function assign($key, $value, $general = false)
	{
		if (!$general)
		{
			$this->assignedData[$key] = $value;
		}
		else
		{
			$this->assignedViewData[$key] = $value;
		}
	}

	/**
	 * Assign cookies to response
	 * @param string $key
	 * @param mixed $value
	 * @param int $timeValid
	 */
	public function assignCookie($key, $value, $timeValid = null)
	{
		if (is_null($timeValid))
		{
			$this->assignedCookies[] = Cookie::make($key, $value);
		}
		else
		{
			$this->assignedCookies[] = Cookie::make($key, $value, $timeValid);
		}
	}

	/**
	 * Assign headers to response
	 * @param string $key
	 * @param mixed $value
	 */
	public function assignHeader($key, $value)
	{
		$this->assignedHeaders[$key] = $value;
	}

	/**
	 * Fetch the build response
	 * @param mixed $response
	 */
	private function attachExtras(&$response)
	{
		//Assign statusCode
		$response->setStatusCode($this->statusCode);

		//Assign cookie
		foreach ($this->assignedCookies as $cookie)
		{
			$response->withCookie($cookie);
		}

		//Assign headers
		foreach ($this->assignedHeaders as $key => $value)
		{
			$response->header($key, $value);
		}
	}

	/**
	 * Return content
	 * @param mixed $content
	 * @return ResponseFactory
	 */
	public function returnContent($content)
	{
		$response = Response::make($content);
		$this->attachExtras($response);

		return $response;
	}

	/**
	 * Return view
	 * @param string $view
	 * @return \Illuminate\Http\Response
	 */
	public function returnView($view)
	{
		$view = Response::view($view, array_merge($this->assignedData, $this->assignedViewData));
		$this->attachExtras($view, true);

		return $view;
	}

	/**
	 * Return json-data
	 * @return JsonResponse
	 */
	public function returnJson()
	{
		$json = Response::json($this->assignedData);
		$this->attachExtras($json);

		return $json;
	}

	/**
	 * Return a download
	 * @param string $file
	 * @param string $name
	 * @return BinaryFileResponse
	 */
	public function returnDownload($file, $name = null)
	{
		$download = Response::download(FileHelper::getPath($file), $name);
		$this->attachExtras($download);

		return $download;
	}

	/**
	 * Redirect to the specified url
	 * @param string $uri
	 * @param bool|array $input
	 * @return RedirectResponse
	 */
	public function returnRedirect($uri, $input = null)
	{
		$redirect = redirect($uri);

		if ($input === true)
		{
			$redirect = $redirect->withInput();
		}
		else
		{
			$redirect = $redirect->withInput($input);
		}

		return $redirect;
	}

	/**
	 * 404
	 */
	public function return404()
	{
		return abort(404);
	}
}
