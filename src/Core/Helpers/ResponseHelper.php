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
	 * The response
	 * @var array
	 */
	private $response = array();

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
		if (!is_a($response, \Illuminate\View\View::class))
		{
			//Assign statusCode
			$response->setStatusCode($this->statusCode);

			//Assign headers
			foreach ($this->assignedHeaders as $key => $value)
			{
				$response->header($key, $value);
			}
		}

		//Assign cookie
		foreach ($this->assignedCookies as $cookie)
		{
			$response->withCookie($cookie);
		}
	}

	/**
	 * Return content
	 * @param mixed $content
	 */
	public function responseContent($content)
	{
		if (!isset($this->response['content']))
		{
			$this->response['content'] = Response::make($content);
		}
	}

	/**
	 * Return view
	 * @param string $view
	 * @param string $key
	 * @return \Illuminate\Http\Response
	 */
	public function responseView($key = null, $view)
	{
		if (is_null($key))
		{
			$key = '_';
		}

		if (!isset($this->response['view']))
		{
			$this->response['view'] = array($key => $view);
		}
		else
		{
			$this->response['view'][$key] = $view;
		}
	}

	/**
	 * Return json-data
	 * @return JsonResponse
	 */
	public function responseJson()
	{
		$this->response['json'] = true;
	}

	/**
	 * Return a download
	 * @param string $file
	 * @param string $name
	 * @return BinaryFileResponse
	 */
	public function responseDownload($file, $name = null)
	{
		if (!isset($this->response['download']))
		{
			$this->response['download'] = Response::download(FileHelper::getPath($file), $name);
		}
	}

	/**
	 * Redirect to the specified url
	 * @param string $uri
	 * @param bool|array $input
	 * @return RedirectResponse
	 */
	public function responseRedirect($uri, $input = null)
	{
		if (!isset($this->response['redirect']))
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

			$this->response['redirect'] = $redirect;
		}
	}

	/**
	 * 404
	 */
	public function response404()
	{
		if (!isset($this->response['404']))
		{
			$this->response['404'] = abort(404);
		}
	}

	/**
	 * Unauthorized
	 */
	public function responseUnauthorized()
	{
		if (!isset($this->response['403']))
		{
			$this->response['403'] = abort(403);
		}
	}

	/**
	 * Get the response
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse()
	{
		$response = null;

		if (isset($this->response['404']))
		{
			$response = $this->response['404'];
		}
		elseif (isset($this->response['403']))
		{
			$response = $this->response['403'];
		}
		elseif (isset($this->response['redirect']))
		{
			$response = $this->response['redirect'];
		}
		elseif (isset($this->response['download']))
		{
			$response = $this->response['download'];
		}
		elseif (isset($this->response['json']))
		{
			$response = Response::json($this->assignedData);
		}
		elseif (isset($this->response['view']))
		{
			$data = array_merge($this->assignedData, $this->assignedViewData);
			ksort($this->response['view']);

			foreach ($this->response['view'] as $key => $view)
			{
				if (is_null($response))
				{
					$response = View::make($view, $data);
				}
				else
				{
					$response = $response->nest($key, $view, $data);
				}
			}
		}
		elseif (isset($this->response['content']))
		{
			$response = $this->response['content'];
		}

		if (!is_null($response))
		{
			$this->attachExtras($response);
		}

		return $response;
	}
}
