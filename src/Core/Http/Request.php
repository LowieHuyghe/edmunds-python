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
use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Input;
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
 * @property string $referrer Return the referrer
 * @property string $url Return the url
 * @property string $fullUrl Return the full url
 * @property string $root Return the root of the application
 * @property string $userAgent Return the user agent
 * @property string $path Return the path of the request
 * @property bool $ajax Check if call was ajax
 * @property bool $secure Check if call was over https
 * @property bool $json Check if call wants json
 * @property bool $xml Check if call wants xml
 * @property string $method Return method of request
 * @property array $segments Return method of route
 * @property string $route Return the route
 * @property string $host Return the host
 * @property User $user Return the user
 */
class Request extends BaseStructure
{
	/**
	 * Instance of the Request-structure
	 * @var Request
	 */
	private static $instance;

	/**
	 * Fetch instance of the Request-structure
	 * @return Request
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Request(app('request'));
		}

		return self::$instance;
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;
	}

	/**
	 * Return the ip of the visitor
	 * @return string
	 */
	protected function getIpAttribute()
	{
		$ip = $this->request->ip();

		if (in_array($ip, array('127.0.0.1', '10.0.2.2', '192.168.99.1')) && app()->isLocal()
			|| is_null($ip) && app()->isTesting())
		{
			$ip = '213.118.118.244';
		}
		return $ip;
	}

	/**
	 * Return the referrer
	 * @return string
	 */
	protected function getReferrerAttribute()
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
	 * Return the path of the request
	 * @return string
	 */
	protected function getPathAttribute()
	{
		return $this->request->path();
	}

	/**
	 * Return a variable from server
	 * @param string $key
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
		return $this->request->wantsJson()
			|| (Input::getInstance()->has('output') && strtolower(Input::getInstance()->get('output')) == 'json');
	}

	/**
	 * Check if call wants xml
	 * @return bool
	 */
	protected function getXmlAttribute()
	{
		return (Input::getInstance()->has('output') && strtolower(Input::getInstance()->get('output')) == 'xml');
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
	 * Return the host
	 * @return string
	 */
	protected function getHostAttribute()
	{
		return $this->request->getHttpHost();
	}

	/**
	 * Return the user
	 * @return user
	 */
	protected function getUserAttribute()
	{
		return $this->request->user();
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

}
