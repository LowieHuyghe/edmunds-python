<?php

	if (defined('trans'))
	{
		/**
		 * Translate a string
		 * @param string $message
		 * @param array $parameters
		 * @return string
		 */
		function trans($message, $parameters = array(), $locale = null)
		{
			return \Core\Helpers\TranslationHelper::getInstance()->trans($message, $parameters, $locale);
		}
	}


	if (defined('trans_choice'))
	{
		/**
		 * Translate a string with pluralization
		 * @param string $message
		 * @param int $count
		 * @param array $parameters
		 * @return string
		 */
		function trans_choice($message, $count, $parameters = array(), $locale = null)
		{
			return \Core\Helpers\TranslationHelper::getInstance()->transChoice($message, $count, $parameters, $locale);
		}
	}

	/**
	 * Generate an uuid
	 * @param int $version
	 * @param string $namespace
	 * @param string $name
	 * @return string
	 */
	function generate_uuid($version = 4, $namespace = null, $name = null)
	{
		if ($version == 3)
		{
			if(preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) !== 1) return null;

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
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) !== 1) return null;

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

	if (defined('config'))
	{
		/**
		 * Get / set the specified configuration value.
		 *
		 * If an array is passed as the key, we will assume you want to set an array of values.
		 *
		 * @param  array|string  $key
		 * @param  mixed  $default
		 * @return mixed
		 */
		function config($key = null, $default = null)
		{
			if (is_null($key)) {
				return app('config');
			}

			if (is_array($key)) {
				return app('config')->set($key);
			}

			app()->configure(explode('.', $key)[0]);
			return app('config')->get($key, $default);
		}
	}

	/**
	 * Dump the passed variables and continue
	 */
	function dc()
	{
		array_map(function ($x) {
			(new \Illuminate\Support\Debug\Dumper())->dump($x);
		}, func_get_args());
	}