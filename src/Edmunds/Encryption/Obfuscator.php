<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Edmunds\Encryption;

use Edmunds\Application;
use Edmunds\Bases\Structures\BaseStructure;

/**
 * Obfuscator
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Obfuscator extends BaseStructure
{
	/**
	 * Constructor
	 * @param Application $app
	 */
	public function __construct($key)
	{
		// determine int key
		$intKey = '';
		foreach (str_split($key) as $char)
		{
			$intKey .= last(str_split(ord($char)));
		}
		$this->keyShort = (int) substr($intKey, -9);
		$this->keyLong = (int) substr($intKey, 0, 11);
	}

	/**
	 * Encode the value
	 * @param  int $value
	 * @return int
	 */
	public function encode($value)
	{
		$value = $value ^ $this->keyShort;

		$value = decbin($value);
		$value = '1' . strrev($value);
		$value = bindec($value);

		return $value;
	}

	/**
	 * Decode the value
	 * @param  int $value
	 * @return int
	 */
	public function decode($value)
	{
		$value = decbin($value);
		$value = strrev(substr($value, 1));
		$value = bindec($value);

		$value = $value ^ $this->keyShort;

		return $value;
	}

	/**
	 * Scramble the value
	 * @param  int $value
	 * @return string
	 */
	public function scramble($value)
	{
		$value = $value ^ $this->keyLong;

		$value = decbin($value);
		$value = '1' . strrev($value);
		$value = base_convert($value, 2, 36);

		return $value;
	}

	/**
	 * Unscramble the value
	 * @param  string $value
	 * @return int
	 */
	public function unscramble($value)
	{
		$value = base_convert($value, 36, 2);
		$value = strrev(substr($value, 1));
		$value = bindec($value);

		$value = $value ^ $this->keyLong;

		return $value;
	}
}