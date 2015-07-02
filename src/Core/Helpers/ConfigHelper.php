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

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LH\Core\Models\FileEntry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for the configuration
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ConfigHelper extends BaseHelper
{
	/**
	 * @var array
	 */
	private static $config = array();

	/**
	 * Get the config for a certain
	 * @param string $position
	 * @return mixed
	 */
	public static function get($position)
	{
		if (!$position)
		{
			throw new \Exception('No valid config-position given.');
		}

		//Fetch data
		$position = explode('.', $position);
		$file = array_shift($position);
		$config = self::getConfigFile($file);

		//Fetch the right data
		foreach ($position as $key)
		{
			$config = $config[$key];
		}

		//Return config
		return $config;
	}

	/**
	 * Return the config for a certain file
	 * @param string $file
	 * @return mixed
	 */
	private static function getConfigFile($file)
	{
		//Fetch the config
		if (!isset(self::$config[$file]))
		{
			$filePath = __DIR__ . "/../_config/$file.php";
			if (file_exists($filePath))
			{
				self::$config[$file] = require $filePath;
			}
			else
			{
				self::$config[$file] = false;
			}
		}

		//If file does not exist throw error
		if (self::$config[$file] === false)
		{
			throw new \Exception("Configuration file $file does not exist.");
		}

		//Return config
		return self::$config[$file];
	}
}
