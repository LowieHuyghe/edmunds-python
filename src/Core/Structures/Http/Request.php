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

namespace LH\Core\Structures\Http;
use Illuminate\Support\Facades\App;
use LH\Core\Structures\BaseStructure;
use LH\Core\Structures\Client\Session;

/**
 * The helper for the request
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $ip Return the ip of the visitor
 * @property string $referer Return the referer
 * @property string $url Return the url
 * @property string $fullUrl Return the full url
 * @property string $root Return the root of the application
 * @property string $userAgent Return the user agent
 * @property Session $session Return the session
 * @property string $path Return the path of the request
 * @property bool $ajax Check if call was ajax
 * @property bool $secure Check if call was over https
 * @property bool $json Check if call wants json
 */
class Request extends BaseStructure
{
	/**
	 * Instance of the response-helper
	 * @var Request
	 */
	private static $instance;

	/**
	 * Initialize the request-helper
	 * @param \Illuminate\Http\Request $request
	 */
	public static function initialize($request)
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Request($request);
		}
	}

	/**
	 * Fetch instance of the response-helper
	 * @return Request
	 */
	public static function getInstance()
	{
		return self::$instance;
	}

	/**
	 * The request
	 * @var \Illuminate\Http\Request
	 */
	private $request;

	/**
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	 * Return the ip of the visitor
	 * @return string
	 */
	protected function getIpAttribute()
	{
		return $this->request->ip();
	}

	/**
	 * Return the referer
	 * @return string
	 */
	protected function getRefererAttribute()
	{
		return $this->getServer('HTTP_REFERER');
	}

	/**
	 * Return the url
	 * @return string
	 */
	protected function getUrlAttribute()
	{
		return $this->request->url();
	}

	/**
	 * Return the full url
	 * @return string
	 */
	protected function getFullUrlAttribute()
	{
		return $this->request->fullUrl();
	}

	/**
	 * Return the root of the application
	 * @return string
	 */
	protected function getRootAttribute()
	{
		return $this->request->root();
	}

	/**
	 * Return the user agent
	 * @return string
	 */
	protected function getUserAgentAttribute()
	{
		return $this->getServer('HTTP_USER_AGENT');
	}

	/**
	 * Return the session
	 * @return Session
	 */
	protected function getSessionAttribute()
	{
		return $this->request->session();
	}

	/**
	 * Return the value of a cookie
	 * @param string $key
	 * @param string $default
	 * @return array|string
	 */
	public function getCookie($key = null, $default = null)
	{
		return $this->request->cookie($key, $default);
	}

	/**
	 * Return the path of the request
	 * @return string
	 */
	protected function getPathAttribute()
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
	protected function getAjaxAttribute()
	{
		return $this->request->ajax();
	}

	/**
	 * Check if call was over https
	 * @return bool
	 */
	protected function getSecureAttribute()
	{
		return $this->request->secure();
	}

	/**
	 * Check if call wants json
	 * @return bool
	 */
	protected function getJsonAttribute()
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
