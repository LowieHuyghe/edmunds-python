<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Routing;

use Laravel\Lumen\Routing\UrlGenerator as BaseUrlGenerator;



/**
 * The url generator
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