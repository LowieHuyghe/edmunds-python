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

namespace Core\Helpers;

/**
 * The helper to use Encryption
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class EncryptionHelper extends BaseHelper
{
	/**
	 * Instance of the pushbullet-helper
	 * @var EncryptionHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the encryption-helper
	 * @return EncryptionHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new EncryptionHelper();
		}

		return self::$instance;
	}

	/**
	 * Encrypt value
	 * @param string $value
	 * @return string
	 */
	public function encrypt($value)
	{
		return app('crypt')->encrypt($value);
	}

	/**
	 * Decrypt value
	 * @param string $value
	 * @return string
	 */
	public function decrypt($value)
	{
		return app('crypt')->decrypt($value);
	}

}