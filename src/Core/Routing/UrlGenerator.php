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

namespace Core\Routing;

use Laravel\Lumen\Routing\UrlGenerator as BaseUrlGenerator;



/**
 * The url generator
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class UrlGenerator extends BaseUrlGenerator
{
	/**
	 * Get the URL for the previous request.
	 * @return string
	 */
	public function previous()
	{
		$referrer = $this->app->make('request')->headers->get('referer');

		$url = $referrer ? $this->to($referrer) : ($this->app->isStateful() ? $this->getPreviousUrlFromSession() : null);

		return $url ?: $this->to('/');
	}

	/**
	 * Get the previous URL from the session if possible.
	 * @return string|null
	 */
	protected function getPreviousUrlFromSession()
	{
		$session = $this->app['session'];

		return $session ? $session->previousUrl() : null;
	}
}