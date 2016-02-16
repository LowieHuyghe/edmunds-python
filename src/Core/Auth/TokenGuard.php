<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Auth;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

/**
 * The token guard
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 *
 * @property bool $loggedIn
 * @property User $user
 * @property int $loginAttempts
 */
class TokenGuard extends \Illuminate\Auth\TokenGuard
{
	/**
	 * The user we last attempted to retrieve.
	 *
	 * @var \Illuminate\Contracts\Auth\Authenticatable
	 */
	protected $lastAttempted;

	/**
	 * The event dispatcher instance.
	 *
	 * @var \Illuminate\Contracts\Events\Dispatcher
	 */
	protected $events;

	/**
	 * Indicates if the logout method has been called.
	 *
	 * @var bool
	 */
	protected $loggedOut = false;

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
		return parent::user();
	}

	/**
	 * Get the ID for the currently authenticated user.
	 *
	 * @return int|null
	 */
	public function id()
	{
		if ($this->loggedOut)
		{
			return;
		}
		return parent::id();
	}

	/**
	 * Set the current user.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @return void
	 */
	public function setUser(AuthenticatableContract $user)
	{
		parent::setUser($user);

		$this->loggedOut = false;
	}

	/**
	 * Log a user into the application without sessions or cookies.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function once(array $credentials = [])
	{
		if ($this->validate($credentials))
		{
			$this->setUser($this->lastAttempted);

			return true;
		}

		return false;
	}

	/**
	 * Validate a user's credentials.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validate(array $credentials = [])
	{
		return $this->attempt($credentials, false, false);
	}

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array  $credentials
	 * @param  bool   $remember
	 * @param  bool   $login
	 * @return bool
	 */
	public function attempt(array $credentials = [], $remember = true, $login = true)
	{
		$this->fireAttemptEvent($credentials, $remember, $login);

		$this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

		// If an implementation of UserInterface was returned, we'll ask the provider
		// to validate the user against the given credentials, and if they are in
		// fact valid we'll log the users into the application and return true.
		if ($this->hasValidCredentials($user, $credentials))
		{
			if ($login)
			{
				$this->login($user, $remember);
			}

			return true;
		}

		return false;
	}

	/**
	 * Fire the attempt event with the arguments.
	 *
	 * @param  array  $credentials
	 * @param  bool  $remember
	 * @param  bool  $login
	 * @return void
	 */
	protected function fireAttemptEvent(array $credentials, $remember, $login)
	{
		if (isset($this->events))
		{
			$this->events->fire(new \Illuminate\Auth\Events\Attempting(
				$credentials, $remember, $login
			));
		}
	}

	/**
	 * Register an authentication attempt event listener.
	 *
	 * @param  mixed  $callback
	 * @return void
	 */
	public function attempting($callback)
	{
		if (isset($this->events))
		{
			$this->events->listen(\Illuminate\Auth\Events\Attempting::class, $callback);
		}
	}

	/**
	 * Get the last user we attempted to authenticate.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable
	 */
	public function getLastAttempted()
	{
		return $this->lastAttempted;
	}

	/**
	 * Log a user into the application.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  bool  $remember
	 * @return void
	 */
	public function login(AuthenticatableContract $user, $remember = false)
	{
		// If the user should be permanently "remembered" by the application we will
		// queue a permanent cookie that contains the encrypted copy of the user
		// identifier. We will then decrypt this later to retrieve the users.
		if ($remember && !$user->api_token)
		{
			$this->refreshApiToken($user);
		}

		// If we have an event dispatcher instance set we will fire an event so that
		// any listeners will hook into the authentication events and run actions
		// based on the login and logout events fired from the guard instances.
		$this->fireLoginEvent($user, $remember);

		$this->setUser($user);
	}

	/**
	 * Fire the login event if the dispatcher is set.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  bool  $remember
	 * @return void
	 */
	protected function fireLoginEvent($user, $remember = false)
	{
		if (isset($this->events))
		{
			$this->events->fire(new \Illuminate\Auth\Events\Login($user, $remember));
		}
	}

	/**
	 * Determine if the user matches the credentials.
	 *
	 * @param  mixed  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	protected function hasValidCredentials($user, $credentials)
	{
		return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return void
	 */
	public function logout()
	{
		$user = $this->user();

		if (!is_null($this->user))
		{
			$this->refreshApiToken($this->user);
		}

		if (isset($this->events))
		{
			$this->events->fire(new \Illuminate\Auth\Events\Logout($user));
		}

		// Once we have fired the logout event we will clear the users out of memory
		// so they are no longer available as the user is no longer considered as
		// being signed into this application and should not be available here.
		$this->user = null;

		$this->loggedOut = true;
	}

	/**
	 * Refresh the "api" token for the user.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @return void
	 */
	protected function refreshApiToken(AuthenticatableContract $user)
	{
		$user->api_token = $token = Str::random(60);
		$user->save();
	}

	/**
	 * Get the event dispatcher instance.
	 *
	 * @return \Illuminate\Contracts\Events\Dispatcher
	 */
	public function getDispatcher()
	{
		return $this->events;
	}

	/**
	 * Set the event dispatcher instance.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function setDispatcher(Dispatcher $events)
	{
		$this->events = $events;
	}
}
