<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Encryption;

use Edmunds\Bases\Providers\BaseServiceProvider;
use Edmunds\Encryption\Obfuscator;

/**
 * Obfuscator ServiceProdivder
 */
class ObfuscatorServiceProvider extends BaseServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('obfuscator', function ($app)
		{
			$config = $app->make('config')->get('app');

			$key = $config['key'];

			return new Obfuscator($key);
		});
	}
}