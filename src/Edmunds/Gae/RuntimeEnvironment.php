<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae;

use Edmunds\Patterns\Singleton;

/**
 * Environment check for Google App Engine
 */
class RuntimeEnvironment
{
	use Singleton;

	/**
	 * 'true' if running on GAE.
	 *
	 * @var bool
	 */
	protected $gaeEnvironment;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->detectGae();
	}

	/**
	 * Detect if the application is running on GAE.
	 */
	protected function detectGae()
	{
		$appIdentityService = 'google\appengine\api\app_identity\AppIdentityService';

		if ( ! class_exists($appIdentityService))
		{
			$this->gaeEnvironment = false;
			return;
		}

		$this->gaeEnvironment = (bool) $appIdentityService::getDefaultVersionHostname();
	}

	/**
	 * Returns 'true' if running on GAE.
	 *
	 * @return bool
	 */
	public function isGae()
	{
		return $this->gaeEnvironment;
	}
}