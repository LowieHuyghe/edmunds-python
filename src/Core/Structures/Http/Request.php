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

namespace Core\Structures\Http;
use Core\Structures\BaseStructure;
use Core\Structures\Client\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
 * @property string $method Return method of request
 * @property array $segments Return method of route
 * @property string $route Return the route
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
	public static function initialize(&$request)
	{
		self::$instance = new Request($request);
	}

	/**
	 * Fetch instance of the response-helper
	 * @return Request
	 */
	public static function current()
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
	public function __construct(&$request)
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
	 * Return method of route
	 * @return string
	 */
	protected function getMethodAttribute()
	{
		return $this->request->method();
	}

	/**
	 * Return segments of the route
	 * @return array
	 */
	protected function getSegmentsAttribute()
	{
		return $this->request->segments();
	}

	/**
	 * Return the route
	 * @return string
	 */
	protected function getRouteAttribute()
	{
		return $this->request->route()[2]['route'];
	}

	/**
	 * Retrieve an input item from the request.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return string|string[]
	 */
	public function input($key = null, $default = null)
	{
		return $this->request->input($key, $default);
	}

	/**
	 * Get a subset of the items from the input data.
	 *
	 * @param  array  $keys
	 * @return array
	 */
	public function inputOnly($keys)
	{
		return $this->request->only($keys);
	}

	/**
	 * Get all of the input except for a specified array of items.
	 *
	 * @param  array  $keys
	 * @return array
	 */
	public function inputExcept($keys)
	{
		return $this->request->except($keys);
	}

	/**
	 * Determine if the request contains a non-empty value for an input item.
	 *
	 * @param  string|string[]  $key
	 * @return bool
	 */
	public function hasInput($key)
	{
		return $this->request->has($key);
	}

	/**
	 * Retrieve a file from the request.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return UploadedFile|UploadedFile[]
	 */
	public function file($key = null, $default = null)
	{
		return $this->request->file($key, $default);
	}

	/**
	 * Determine if the uploaded data contains a file.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function hasFile($key)
	{
		return $this->request->hasFile($key);
	}

	/**
	 * Check if local environment
	 * @return bool
	 */
	public function isLocalEnvironment()
	{
		return app()->environment() == 'local' && env('APP_DEBUG');
	}

	/**
	 * Check if production environment
	 * @return bool
	 */
	public function isProductionEnvironment()
	{
		return app()->environment() == 'production';
	}

	/**
	 * Check if testing environment
	 * @return bool
	 */
	public function isTestingEnvironment()
	{
		return app()->environment() == 'testing';
	}

}
