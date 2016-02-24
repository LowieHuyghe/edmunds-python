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

use Core\Auth\Models\LoginAttempt;
use Core\Auth\Models\PasswordReset;
use Core\Auth\Models\User;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Request;
use Core\Localization\Format\DateTime;
use Illuminate\Auth\AuthManager;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * The structure for authentication
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.
 *
 * @property bool $loggedIn
 * @property User $user
 * @property int $loginAttempts
 * @property int $attemptsMax
 * @property int $lockoutTime
 * @property int $attemptsCount
 * @property int $attemptsLeft
 * @property bool $attemptsTooMany
 */
class Auth extends BaseStructure
{
	/**
	 * Instance of the auth-structure
	 * @var Auth
	 */
	private static $instance;

	/**
	 * Fetch instance of the auth-structure
	 * @return Auth
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Auth(Request::getInstance());
		}

		return self::$instance;
	}

	/**
	 * The current request
	 * @var Request
	 */
	private $request;

	/**
	 * The user provider
	 * @var UserProvider
	 */
	private $provider;

	/**
	 * Constructor
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;

		$this->attemptsMax = 5;
		$this->lockoutTime = 60;
	}

	/**
	 * Check if visitor is logged in
	 * @return bool
	 */
	protected function getLoggedInAttribute()
	{
		return $this->getGuard()->check();
	}

	/**
	 * Get the current logged in user
	 * @return User
	 */
	protected function getUserAttribute()
	{
		return $this->getGuard()->user();
	}

	/**
	 * Get the number of attempts that were made
	 * @return int
	 */
	protected function getAttemptsCountAttribute()
	{
        return app(RateLimiter::class)->attempts($this->getAttemptsKey());
	}

	/**
	 * Get the number of attempts that are left
	 * @return int
	 */
	protected function getAttemptsLeftAttribute()
	{
        return app(RateLimiter::class)->retriesLeft($this->getAttemptsKey(), $this->attemptsMax);
	}

	/**
	 * Check if too many attempts
	 * @return bool
	 */
	protected function getAttemptsTooManyAttribute()
	{
		return app(RateLimiter::class)->tooManyAttempts($this->getAttemptsKey(), $this->attemptsMax, $this->lockoutTime / 60);
	}

	/**
	 * Increment attempts
	 */
	protected function incrementAttempts()
	{
        app(RateLimiter::class)->hit($this->getAttemptsKey());
	}

	/**
	 * Get the key used to store the attempts
	 * @return string
	 */
	protected function getAttemptsKey()
	{
		return $this->request->ip;
	}

	/**
	 * Clear the number of attempts
	 */
	public function clearAttempts()
	{
        app(RateLimiter::class)->clear($this->getAttemptsKey());
	}

	/**
	 * Log a user in
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @param bool $remember
	 * @return bool
	 */
	public function login($email, $password, $once = false, $remember = false)
	{
		// check if too many attempts
		if ($this->attemptsTooMany)
		{
			return false;
		}

		// attempt login
		$credentials = array('email' => $email, 'password' => $password);
		if ($once)
		{
			$loggedIn = $this->getGuard()->once($credentials);
		}
		else
		{
			$loggedIn = $this->getGuard()->attempt($credentials, $remember);
		}

		// clear or increment attempts
		if ($loggedIn)
		{
			$this->clearAttempts();
		}
		else
		{
			$this->incrementAttempts();
		}

		//Return result
		return $loggedIn;
	}

	/**
	 * Login a user
	 * @param  User  $user
	 * @param  boolean $remember
	 */
	public function loginUser($user, $remember = false)
	{
		$this->getGuard()->login($user, $remember);
	}

	/**
	 * Log a user in
	 * @param User $user
	 */
	public function setUser($user)
	{
		$this->getGuard()->setUser($user);
	}

	/**
	 * Log the current user out
	 */
	public function logout()
	{
		$this->getGuard()->logout();
	}

	/**
	 * Get the guard
	 * @return Guard
	 */
	public function getGuard()
	{
		return app('auth')->guard(null);
	}

}
