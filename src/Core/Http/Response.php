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

namespace Core\Http;

use Core\Bases\Responses\BaseResponse;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Input;
use Core\Http\Request;
use Core\Http\Responses\ContentResponse;
use Core\Http\Responses\DownloadResponse;
use Core\Http\Responses\JsonResponse;
use Core\Http\Responses\RedirectResponse;
use Core\Http\Responses\ViewResponse;
use Core\Http\Responses\XmlResponse;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * The helper responsible for the response
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $statusCode
 * @property int $outputType
 */
class Response extends BaseStructure
{
	const	TYPE_VIEW			= 1,
			TYPE_JSON			= 2,
			TYPE_XML			= 3;

	/**
	 * Instance of the Response-structure
	 * @var Response
	 */
	private static $instance;

	/**
	 * Fetch instance of the Response-structure
	 * @return Response
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Response(Request::getInstance());
		}

		return self::$instance;
	}

	/**
	 * The request
	 * @var Request
	 */
	protected $request;

	/** @var array Assigned data for response */
	protected $assignments = array();
	/** @var Cookie[] Assigned cookies for response */
	protected $cookies = array();
	/** @var array Assigned headers for response */
	protected $headers = array();

	/** @var BaseResponse */
	protected $redirectResponse;
	/** @var BaseResponse */
	protected $downloadResponse;
	/** @var BaseResponse */
	protected $contentResponse;
	/** @var BaseResponse */
	protected $viewResponse;

	/**
	 * Constructor
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;

		$this->statusCode = 200;
		$this->outputType = self::TYPE_VIEW;
	}

	/**
	 * Assign data to response
	 * @param string|array $key
	 * @param mixed $value
	 */
	public function assign($key, $value = null)
	{
		if (is_array($key))
		{
			foreach ($key as $valueKey => $value)
			{
				$this->assign($valueKey, $value);
			}
		}
		else
		{
			$this->assignments[$key] = $value;
		}
	}

	/**
	 * Get the assignments
	 * @return array|mixed
	 */
	public function getAssignment($key = null)
	{
		if ($key)
		{
			return $this->assignments[$key];
		}
		else
		{
			return $this->assignments;
		}
	}

	/**
	 * Check if has assignment
	 * @return bool
	 */
	public function hasAssignment($key)
	{
		return isset($this->assignments[$key]);
	}

	/**
	 * Return view
	 * @param string $view
	 * @param string $key
	 */
	public function render($key = null, $view = null)
	{
		if (!isset($this->viewResponse))
		{
			$this->viewResponse = new ViewResponse();
		}
		$this->viewResponse->addView($key, $view);
	}

	/**
	 * Assign a download
	 * @param string $filePath
	 * @param string $name
	 */
	public function download($filePath, $name = null)
	{
		$this->downloadResponse = new DownloadResponse($filePath, $name);
	}

	/**
	 * Assign content
	 * @param mixed $content
	 */
	public function content($content)
	{
		$this->contentResponse = new ContentResponse($content);
	}

	/**
	 * Assign cookies to response
	 * @param string $key
	 * @param mixed $value
	 * @param int $minutes
	 */
	public function cookie($key, $value, $minutes = 0)
	{
		if ($minutes)
		{
			$this->cookies[] = cookie($key, $value, $minutes);
		}
		else
		{
			$this->cookies[] = cookie()->forever($key, $value);
		}
	}

	/**
	 * Assign headers to response
	 * @param string|array $key
	 * @param mixed $value
	 */
	public function header($key, $value = null)
	{
		if (is_array($key))
		{
			foreach ($key as $valueKey => $value)
			{
				$this->header($valueKey, $value);
			}
		}
		else
		{
			$this->headers[$key] = $value;
		}
	}

	/**
	 * Redirect to the specified url
	 * @param string $uri
	 * @param bool|array $input
	 * @param bool $saveIntendedRoute
	 * @param bool $gotoIntendedRoute
	 */
	public function redirect($uri, $input = null, $saveIntendedRoute = false, $gotoIntendedRoute = false)
	{
		//Go to the intended route that was saved
		if ($gotoIntendedRoute)
		{
			if ($this->request->session->has('intended_route'))
			{
				$uri = $this->request->session->get('intended_route');
				$this->request->session->remove('intended_route');
			}
		}
		//Save the route the user intended to go
		if ($saveIntendedRoute)
		{
			$this->request->session->set('intended_route', $this->request->path);
		}

		$this->redirectResponse = new RedirectResponse($uri, $input);

		$this->send();
	}

	/**
	 * Abort the request but send response
	 */
	public function send()
	{
		abort(200);
	}

	/**
	 * Get the response
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse()
	{
		//Redirect response
		if (isset($this->redirectResponse)) $response = $this->redirectResponse;
		//Download response
		elseif (isset($this->downloadResponse)) $response = $this->downloadResponse;
		//Content response
		elseif (isset($this->contentResponse)) $response = $this->contentResponse;

		//View/Xml response
		elseif (isset($this->viewResponse) && in_array($this->outputType, array(self::TYPE_VIEW, self::TYPE_XML)))
		{
			//Add header for xml-response
			if ($this->outputType == self::TYPE_XML)
			{
				$this->header('Content-Type', 'application/xml');
			}

			$response = $this->viewResponse;
		}

		//Json response
		elseif ($this->outputType == self::TYPE_JSON)
		{
			if (!isset($this->assignments['html']) && isset($this->viewResponse))
			{
				$this->assignments['html'] = $this->viewResponse->getRendered($this->assignments);
			}
			$response = new JsonResponse();
		}

		//Emtpy response
		else
		{
			$response = new BaseResponse();
		}

		//Convert to Http-response
		$response = $response->getResponse($this->assignments);
		$this->attachExtras($response);

		//Return the http-response
		return $response;
	}

	/**
	 * Fetch the build response
	 * @param \Illuminate\Http\Response $response
	 */
	protected function attachExtras(&$response)
	{
		//Assign headers
		foreach ($this->headers as $key => $value)
		{
			$response->header($key, $value);
		}

		//Assign cookie
		foreach ($this->cookies as $cookie)
		{
			$response->withCookie($cookie);
		}

		//Set status code
		$response->setStatusCode($this->statusCode);
	}

	/**
	 * Render a view and get the output
	 * @param string|array $view
	 * @param array $input
	 * @return string
	 */
	public function getRenderedView($view, $input = array())
	{
		$response = new ViewResponse();
		if (is_array($view))
		{
			$response->addView($view);
		}
		else
		{
			$response->addView(null, $view);
		}

		return $response->getRendered(array_merge($this->assignments, $input));
	}

}
