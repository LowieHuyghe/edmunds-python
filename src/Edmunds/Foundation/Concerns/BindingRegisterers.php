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

namespace Edmunds\Foundation\Concerns;

use Edmunds\Encryption\ObfuscatorServiceProvider;

/**
 * The RegistersExceptionHandlers concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */
trait BindingRegisterers
{
	/**
	 * register the additional bindings
	 */
	protected function registerAdditionalBindings()
	{
		$this->availableBindings['obfuscator'] = 'registerObfuscatorBindings';
		$this->availableBindings['filesystem'] = 'registerFilesystemBindings';
		$this->availableBindings['mailer'] = 'registerMailBindings';
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

	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerCookieBindings()
	{
		$this->singleton('cookie', function () {
			return $this->loadComponent('session', 'Illuminate\Cookie\CookieServiceProvider', 'cookie');
		});
	}

	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerSessionBindings()
	{
		$this->singleton('session', function () {
			return $this->loadComponent('session', 'Illuminate\Session\SessionServiceProvider');
		});
		$this->singleton('session.store', function () {
			return $this->loadComponent('session', 'Illuminate\Session\SessionServiceProvider', 'session.store');
		});
	}

	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerFilesystemBindings()
	{
		$this->singleton('filesystem', function () {
			return $this->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
		});
	}

	/**
	 * Register container bindings for the application.
	 *
	 * @return void
	 */
	protected function registerMailBindings()
	{
		$this->singleton('mailer', function () {
			return $this->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
		});
	}
}