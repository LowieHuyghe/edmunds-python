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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
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
	 * Assigned data for response
	 * @var array
	 */
	private $assignedData = array();

	/**
	 * Assigned cookies for response
	 * @var Cookie[]
	 */
	private $assignedCookies = null;

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
	public function assign($key, $value)
	{
		$this->assignedData[$key] = $value;
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
		//Assign cookie
		foreach ($this->assignedCookies as $cookie)
		{
			$response->withCookie($cookie);
		}

		//Assign headers
		$generic = is_a($response, '\Illuminate\Http\Response');
		foreach ($this->assignedHeaders as $key => $value)
		{
			if ($generic)
			{
				$response->header($key, $value);
			}
			else
			{
				$response->withHeader($key, $value);
			}
		}
	}

	/**
	 * Return content
	 * @param mixed $content
	 * @return ResponseFactory
	 */
	public function returnContent($content)
	{
		$response = response($content);
		$this->attachExtras($response);

		return $response;
	}

	/**
	 * Return view
	 * @param string $view
	 * @return Response
	 */
	public function returnView($view)
	{
		$view = View::make($view, $this->assignedData);
		$this->attachExtras($view);

		return $view;
	}

	/**
	 * Return json-data
	 * @return JsonResponse
	 */
	public function returnJson()
	{
		return response()->json($this->assignedData);
	}

	/**
	 * Return a download
	 * @param string $file
	 * @param string $name
	 * @return BinaryFileResponse
	 */
	public function returnDownload($file, $name)
	{
		return response()->download(FileHelper::getPath($file), $name);
	}

	/**
	 * Redirect to the specified url
	 * @param string $uri
	 * @param bool|array $input
	 * @return RedirectResponse
	 */
	public function returnRedirect($uri, $input = false)
	{
		$redirect = redirect($uri);

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

	/**
	 * 404
	 */
	public function return404()
	{
		return abort(404);
	}

	/**
	 * 500
	 */
	public function returnFailed()
	{
		return abort(500);
	}
}
