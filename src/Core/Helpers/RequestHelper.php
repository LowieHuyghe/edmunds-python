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
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * The helper for the request
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class RequestHelper extends BaseHelper
{
	/**
	 * Instance of the response-helper
	 * @var RequestHelper
	 */
	private static $instance;

	/**
	 * Initialize the request-helper
	 * @param Request $request
	 */
	public static function initialize($request)
	{
		if (!isset(self::$instance))
		{
			self::$instance = new RequestHelper($request);
		}
	}

	/**
	 * Fetch instance of the response-helper
	 * @return RequestHelper
	 */
	public static function getInstance()
	{
		return self::$instance;
	}

	/**
	 * The request
	 * @var Request
	 */
	private $request;

	/**
	 * @param Request $request
	 */
	private function __construct($request)
	{
		$this->request = $request;
	}

	/**
	 * Return the ip of the visitor
	 * @return string
	 */
	public function getIp()
	{
		return $this->request->ip();
	}

	/**
	 * Return the referer
	 * @return string
	 */
	public function getReferer()
	{
		return $this->getServer('HTTP_REFERER');
	}

	/**
	 * Return the url
	 * @return string
	 */
	public function getUrl()
	{
		return $this->request->url();
	}

	/**
	 * Return the full url
	 * @return string
	 */
	public function getFullUrl()
	{
		return $this->request->fullUrl();
	}

	/**
	 * Return the root of the application
	 * @return string
	 */
	public function getRoot()
	{
		return $this->request->root();
	}

	/**
	 * Return the user agent
	 * @return string
	 */
	public function getUserAgent()
	{
		return $this->getServer('HTTP_USER_AGENT');
	}

	/**
	 * Return the session
	 * @return \Illuminate\Session\Store
	 */
	public function getSession()
	{
		return $this->request->session();
	}

	/**
	 * Return the path of the request
	 * @return string
	 */
	public function getPath()
	{
		return $this->request->path();
	}

	/**
	 * Return a variable from server
	 * @params string $key
	 * @return mixed
	 */
	public function getServer($key)
	{
		return $this->request->server($key);
	}

	/**
	 * Check if call was ajax
	 * @return bool
	 */
	public function isAjax()
	{
		return $this->request->ajax();
	}

	/**
	 * Check if call was over https
	 * @return bool
	 */
	public function isSecure()
	{
		return $this->request->secure();
	}

	/**
	 * Check if call wants json
	 * @return bool
	 */
	public function isJson()
	{
		return $this->request->wantsJson();
	}

	/**
	 * Check if local environment
	 * @return bool
	 */
	public function isLocalEnvironment()
	{
		return App::environment('local');
	}

	/**
	 * Check if production environment
	 * @return bool
	 */
	public function isProductionEnvironment()
	{
		return App::environment('production');
	}

	/**
	 * Check if testing environment
	 * @return bool
	 */
	public function isTestingEnvironment()
	{
		return App::environment('testing');
	}

}
