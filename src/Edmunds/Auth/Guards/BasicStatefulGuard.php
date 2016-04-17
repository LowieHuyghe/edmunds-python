<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Guards;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * The basic guard
 *
 * @property bool $loggedIn
 * @property User $user
 * @property int $loginAttempts
 */
class BasicStatefulGuard extends SessionGuard
{
	/**
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user()
	{
		if ($this->loggedOut)
		{
			return;
		}

		$user = parent::user();

		if (is_null($user))
		{
			if ($this->attemptBasic($this->getRequest(), 'email'))
			{
				$user = $this->lastAttempted;
			}
		}

		return $this->user = $user;
	}
}
