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

namespace Core\Structures;
use Carbon\Carbon;
use Core\Models\Auth\LoginAttempt;
use Core\Models\User;
use Core\Structures\Client\Visitor;
use Core\Structures\Http\Request;

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
	public static function current()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Auth();
		}

		return self::$instance;
	}

	/**
	 * @var User
	 */
	private $loggedInUser = null;

	/**
	 * Check if visitor is logged in
	 * @return bool
	 */
	protected function getLoggedInAttribute()
	{
		return app('auth')->check();
	}

	/**
	 * Get the current logged in user
	 * @return User
	 */
	protected function getUserAttribute()
	{
		if ($this->loggedIn) //Logged in
		{
			$authUser = app('auth')->user(); //Get user

			if (!$this->loggedInUser //There was no logged in user
				|| $this->loggedInUser->id != $authUser->id) //Or userId does not match
			{
				if ($user = User::find($authUser->id)) //Find user
				{
					$this->loggedInUser = $user;
				}
				else
				{
					$this->loggedInUser = null;
					$this->logout();
				}
			}
		}
		elseif ($this->loggedInUser) //Not logged in, but loggedInUser is set
		{
			$this->loggedInUser = null;
		}

		return $this->loggedInUser;
	}

	/**
	 * Get the number of attempts the user has made
	 * @return int
	 */
	protected function getLoginAttemptsAttribute()
	{
		$ip = Request::current()->ip;
		$dateTimeFrom = Carbon::now()->addHours(-7)->toDateTimeString();

		return LoginAttempt::where('ip', '=', $ip)->where('created_at', '>', $dateTimeFrom)->count();
	}

	/**
	 * Log a user in
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @return bool
	 */
	public function loginWithCredentials($email, $password, $once = false)
	{
		$credentials = array('email' => $email, 'password' => $password);

		if ($once)
		{
			$loggedIn = app('auth')->once($credentials);
		}
		else
		{
			$loggedIn = app('auth')->attempt($credentials);
		}

		if ($loggedIn)
		{
			$user = User::where('email', '=', $email)->first();

			$this->loggedInUser = $user;
			Visitor::current()->user = $user;
		}

		//Log attempt
		$loginAttempt = new LoginAttempt();
		$loginAttempt->ip = Request::current()->ip;
		$loginAttempt->type = 'credentials';
		$loginAttempt->email = $email;
		$loginAttempt->password = $password;
		if ($loggedIn && $this->loggedInUser)
		{
			$loginAttempt->user()->associate($this->loggedInUser);
		}
		$loginAttempt->save();

		//Return result
		return $loggedIn;
	}

	/**
	 * Log a user in
	 * @param User $user
	 * @param bool $once
	 * @return bool
	 */
	public function loginWithUser($user, $once = false)
	{
		if ($once || app('auth')->login($user))
		{
			$this->loggedInUser = $user;
			Visitor::current()->user = $user;

			return true;
		}

		return false;
	}

	/**
	 * Log the current user out
	 * @return bool
	 */
	public function logout()
	{
		if (app('auth')->logout())
		{
			$this->loggedInUser = null;
			Visitor::current()->user = null;

			return true;
		}

		return false;
	}

}
