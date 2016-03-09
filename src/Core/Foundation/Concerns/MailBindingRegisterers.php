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

/**
 * The MailBindingRegisterers concern
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 */
trait MailBindingRegisterers
{
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
