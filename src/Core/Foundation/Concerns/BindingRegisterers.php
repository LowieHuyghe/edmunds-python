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

namespace Core\Foundation\Concerns;

use Core\Encryption\ObfuscatorServiceProvider;

/**
 * The RegistersExceptionHandlers concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 */
trait BindingRegisterers
{
	/**
	 * register the additional bindings
	 */
	protected function registerAdditionalBindings()
	{
		$this->availableBindings['obfuscator'] = 'registerObfuscatorBindings';
	}

	/**
	 * Register obfuscator bindings
	 */
	protected function registerObfuscatorBindings()
	{
        $this->singleton('obfuscator', function ()
        {
            return $this->loadComponent('app', ObfuscatorServiceProvider::class, 'obfuscator');
        });
	}
}