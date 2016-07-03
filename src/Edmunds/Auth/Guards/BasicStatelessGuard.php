<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * The basic guard
 *
 * @property bool $loggedIn
 * @property User $user
 * @property int $loginAttempts
 */
class BasicStatelessGuard implements Guard
{
	use GuardHelpers;

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
	 * The request instance.
	 *
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * Indicates if the logout method has been called.
	 *
	 * @var bool
	 */
	protected $loggedOut = false;

	/**
	 * Create a new authentication guard.
	 *
	 * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return void
	 */
	public function __construct(UserProvider $provider, Request $request = null)
	{
		$this->request = $request;
		$this->provider = $provider;
	}

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

		// If we've already retrieved the user for the current request we can just
		// return it back immediately. We do not want to fetch the user data on
		// every call to this method because that would be tremendously slow.
		if ( ! is_null($this->user))
		{
			return $this->user;
		}

		$user = null;

		if ($this->attemptBasic($this->getRequest(), 'email', false, false))
		{
			$user = $this->lastAttempted;
		}

		return $this->user = $user;
	}

    /**
     * Attempt to authenticate using basic authentication.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  string  $field
	 * @param  bool   $remember
	 * @param  bool   $login
     * @return bool
     */
    protected function attemptBasic(Request $request, $field, $remember = false, $login = true)
    {
        if ( ! $request->getUser())
        {
            return false;
        }

        return $this->attempt($this->getBasicCredentials($request, $field), $remember, $login);
    }

    /**
     * Get the credential array for a HTTP Basic request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  string  $field
     * @return array
     */
    protected function getBasicCredentials(Request $request, $field)
    {
        return [$field => $request->getUser(), 'password' => $request->getPassword()];
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

		if ($this->user())
		{
			return $this->user()->getAuthIdentifier();
		}
	}

	/**
	 * Set the current user.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @return void
	 */
	public function setUser(AuthenticatableContract $user)
	{
		$this->user = $user;

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
	public function attempt(array $credentials = [], $remember = false, $login = true)
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
		if ($remember)
		{
			//
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

	/**
	 * Get the current request instance.
	 *
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public function getRequest()
	{
		return $this->request ?: Request::createFromGlobals();
	}

	/**
	 * Set the current request instance.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return $this
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;

		return $this;
	}
}
