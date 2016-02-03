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

namespace Core\Http\Client;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for the input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Input extends BaseStructure
{
	/**
	 * Instance of the response-helper
	 * @var Input
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return Input
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Input(Request::getInstance());
		}

		return self::$instance;
	}

	/**
	 * THe current request
	 * @var Request
	 */
	private $request;

	/**
	 * Constructor
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;
	}

	/**
	 * Retrieve an input item from the request.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return string|string[]
	 */
	public function get($key, $default = null)
	{
		return $this->request->input($key, $default);
	}

	/**
	 * Determine if the request contains a non-empty value for an input item.
	 *
	 * @param  string|string[]  $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->request->hasInput($key);
	}

	/**
	 * Fetch all the data
	 * @return string[]
	 */
	public function all()
	{
		return $this->request->input();
	}

	/**
	 * Fetch certain of the data
	 * @param array $keys
	 * @return array
	 */
	public function only($keys)
	{
		return $this->request->inputOnly($keys);
	}

	/**
	 * Fetch all except of the data
	 * @param array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return $this->request->inputExcept($keys);
	}

	/**
	 * Retrieve file
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return UploadedFile|UploadedFile[]
	 */
	public function file($key, $default = null)
	{
		return $this->request->file($key, $default);
	}

	/**
	 * Check if file exists
	 * @param string $key
	 * @return bool
	 */
	public function hasFile($key)
	{
		return $this->request->hasFile($key);
	}

	/**
	 * Check if file is valid
	 * @param string $key
	 * @return bool
	 */
	public function fileIsValid($key)
	{
		return $this->file($key)->isValid();
	}

	/**
	 * Check if file exists and is valid
	 * @param string $key
	 * @return bool
	 */
	public function fileExistsAndIsValid($key)
	{
		if ($this->hasFile($key))
		{
			return $this->fileIsValid($key);
		}
		return false;
	}
}
