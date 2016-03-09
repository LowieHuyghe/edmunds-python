<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Foundation\Helpers;
use Core\Bases\Helpers\BaseHelper;

/**
 * The helper to use Miscellaneous stuff
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class MiscHelper extends BaseHelper
{
	/**
	 * Generate an uuid
	 * @param int $version
	 * @param string $namespace
	 * @param string $name
	 * @return string
	 */
	public static function generate_uuid($version = 4, $namespace = null, $name = null)
	{
		if ($version == 3)
		{
			if(preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
					'[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $namespace) !== 1) return null;

			// Get hexadecimal components of namespace
			$nhex = str_replace(array('-','{','}'), '', $namespace);

			// Binary Value
			$nstr = '';

			// Convert Namespace UUID to bits
			for($i = 0; $i < strlen($nhex); $i+=2) {
				$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
			}

			// Calculate hash value
			$hash = md5($nstr . $name);

			return sprintf('%08s-%04s-%04x-%04x-%12s',

				// 32 bits for "time_low"
				substr($hash, 0, 8),

				// 16 bits for "time_mid"
				substr($hash, 8, 4),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 3
				(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

				// 48 bits for "node"
				substr($hash, 20, 12)
			);
		}
		elseif ($version == 4)
		{
			return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

				// 32 bits for "time_low"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),

				// 16 bits for "time_mid"
				mt_rand(0, 0xffff),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand(0, 0x0fff) | 0x4000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand(0, 0x3fff) | 0x8000,

				// 48 bits for "node"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
			);
		}
		elseif ($version == 5)
		{
			if(preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
					  '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $namespace) !== 1) return null;

			// Get hexadecimal components of namespace
			$nhex = str_replace(array('-','{','}'), '', $namespace);

			// Binary Value
			$nstr = '';

			// Convert Namespace UUID to bits
			for($i = 0; $i < strlen($nhex); $i+=2) {
				$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
			}

			// Calculate hash value
			$hash = sha1($nstr . $name);

			return sprintf('%08s-%04s-%04x-%04x-%12s',

				// 32 bits for "time_low"
				substr($hash, 0, 8),

				// 16 bits for "time_mid"
				substr($hash, 8, 4),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 5
				(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

				// 48 bits for "node"
				substr($hash, 20, 12)
			);
		}

		return null;
	}

}