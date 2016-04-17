<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Routing;

use Laravel\Lumen\Http\Redirector as BaseRedirector;


/**
 * The redirector
 */
class Redirector extends BaseRedirector
{
	/**
	 * Create a new redirect response to the previous location.
	 *
	 * @param  int    $status
	 * @param  array  $headers
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function back($status = 302, $headers = [])
	{
		$back = $this->app->make('url')->previous();

		return $this->createRedirect($back, $status, $headers);
	}

	/**
	 * Create a new redirect response.
	 *
	 * @param  string  $path
	 * @param  int     $status
	 * @param  array   $headers
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function createRedirect($path, $status, $headers)
	{
		$redirect = parent::createRedirect($path, $status, $headers);

		if ($this->app->isStateful())
		{
			$redirect->setSession($this->app['session.store']);
		}

		return $redirect;
	}
}