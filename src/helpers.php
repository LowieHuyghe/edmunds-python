<?php

	/**
	 * Get the available container instance.
	 *
	 * @param  string  $make
	 * @param  array   $parameters
	 * @return mixed|\Core\Structures\Application
	 */
	function app($make = null, $parameters = [])
	{
		if (is_null($make)) {
			return \Illuminate\Container\Container::getInstance();
		}

		return \Illuminate\Container\Container::getInstance()->make($make, $parameters);
	}

	/**
	 * Translate a string
	 * @param string $message
	 * @param array $parameters
	 * @param string $locale
	 * @return string
	 */
	function trans($message, $parameters = array(), $locale = null)
	{
		return \Core\Structures\Io\Translator::getInstance()->trans($message, $parameters, $locale);
	}
	/**
	 * Translate a string with pluralization
	 * @param string $message
	 * @param int $count
	 * @param array $parameters
	 * @param string $locale
	 * @return string
	 */
	function trans_choice($message, $count, $parameters = array(), $locale = null)
	{
		return \Core\Structures\Io\Translator::getInstance()->transChoice($message, $count, $parameters, $locale);
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

	/**
	 * Dump the passed variables and continue
	 */
	function dd()
	{
		array_map(function ($x) {
			(new \Illuminate\Support\Debug\Dumper())->dump($x);
		}, func_get_args());
	}

	/**
	 * Dump the passed variables and exit
	 */
	function de()
	{
		array_map(function ($x) {
			(new \Illuminate\Support\Debug\Dumper())->dump($x);
		}, func_get_args());
		exit(1);
	}

	/**
	 * Get a faker
	 * @return \Faker\Generator
	 */
	function faker()
	{
		return (new \Faker\Generator());
	}