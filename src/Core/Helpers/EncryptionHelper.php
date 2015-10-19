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
use Core\Bases\Helpers\BaseHelper;

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
	 * Encrypt value
	 * @param string $value
	 * @return string
	 */
	public static function encrypt($value)
	{
		return app('encrypter')->encrypt($value);
	}

	/**
	 * Decrypt value
	 * @param string $value
	 * @return string
	 */
	public static function decrypt($value)
	{
		return app('encrypter')->decrypt($value);
	}

}