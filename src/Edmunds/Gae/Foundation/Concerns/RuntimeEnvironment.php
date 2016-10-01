<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Foundation\Concerns;

use Illuminate\Support\Str;

/**
 * The RuntimeEnvironment concern
 */
trait RuntimeEnvironment
{
	/**
	 * Check if running in production
	 *
	 * @var bool
	 */
	protected $gaeProduction;

	/**
	 * Get or check the current application environment.
	 *
	 * @param  mixed
	 * @return string
	 */
	public function environment()
	{
		if ( ! isset($this->gaeProduction))
		{
			$this->gaeProduction = ! preg_match('/dev~/', getenv('APPLICATION_ID'));
		}

		$env = $this->gaeProduction ? 'production' : 'develop';

		if (func_num_args() > 0)
		{
			$patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

			foreach ($patterns as $pattern)
			{
				if (Str::is($pattern, $env))
				{
					return true;
				}
			}

			return false;
		}

		return $env;
	}
}