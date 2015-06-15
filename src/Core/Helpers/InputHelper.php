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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for the input
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class InputHelper extends BaseHelper
{
	/**
	 * Instance of the response-helper
	 * @var InputHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return InputHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new InputHelper();
		}

		return self::$instance;
	}

	/**
	 * Get value by key
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if (is_null($default))
		{
			return Input::get($key);
		}
		else
		{
			return Input::get($key, $default);
		}
	}

	/**
	 * Check if has value by key
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return Input::has($key);
	}

	/**
	 * Fetch all the data
	 * @return array
	 */
	public function all()
	{
		return Input::all();
	}

	/**
	 * Fetch certain of the data
	 * @param array $keys
	 * @return array
	 */
	public function only($keys)
	{
		return Input::only($keys);
	}

	/**
	 * Fetch all except of the data
	 * @param array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return Input::except($keys);
	}

	/**
	 * Retrieve file
	 * @param string $name
	 * @return UploadedFile
	 */
	public function file($name)
	{
		return Input::file($name);
	}

	/**
	 * Check if file exists
	 * @param string $name
	 * @return bool
	 */
	public function hasFile($name)
	{
		return Input::hasFile($name);
	}

	/**
	 * Check if file is valid
	 * @param string $name
	 * @return bool
	 */
	public function fileIsValid($name)
	{
		return Input::file($name)->isValid();
	}

	/**
	 * Check if file exists and is valid
	 * @param string $name
	 * @return bool
	 */
	public function fileExistsAndIsValid($name)
	{
		if ($this->hasFile($name))
		{
			return $this->fileIsValid($name);
		}
		return false;
	}
}
