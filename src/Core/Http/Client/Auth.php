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

namespace Core\Http\Client;
use Carbon\Carbon;
use Core\Bases\Structures\BaseStructure;
use Core\Models\Auth\LoginAttempt;
use Core\Models\Auth\PasswordReset;
use Core\Models\Auth\AuthToken;
use Core\Models\User;
use Core\Http\Request;

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
		//Login
		$loggedIn = $this->loginWithCredentialsGeneric($email, $password, $once);

		//Log attempt
		$this->saveLoginAttempt('credentials', $email, $password);

		//Return result
		return $loggedIn;
	}

	/**
	 * Login with credentials and retrieve a token on success
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @return string
	 */
	public function loginWithCredentialsForToken($email, $password, $once = false)
	{
		//Login with credentials
		if ($this->loginWithCredentialsGeneric($email, $password, $once))
		{
			//Create auth-token
			$authToken = new AuthToken();
			$authToken->user()->associate($this->loggedInUser);
			$authToken->session_id = Visitor::current()->session->getId();

			//Save and return
			if ($authToken->save())
			{
				return $authToken->token;
			}
		}

		//Log attempt
		$this->saveLoginAttempt('credentials_for_token', $email, $password);

		return null;
	}

	/**
	 * Log the user in with credentials
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @return bool
	 */
	protected function loginWithCredentialsGeneric($email, $password, $once = false)
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

		//Return result
		return $loggedIn;
	}

	/**
	 * Log a user in with token
	 * @param string $token
	 * @param bool $once
	 * @return bool
	 */
	public function loginWithToken($token, $once = false)
	{
		$loggedIn = false;

		//Fetch the token
		if ($authToken = AuthToken::where('token', '=', $token)->first())
		{
			$validUntil = $authToken->updated_at->addMinutes((int) env('CORE_AUTH_AUTHTOKEN_TTL'));

			//Log user in
			if ($loggedIn = $this->loginWithUser($authToken->user, $once))
			{
				//Check if session-id is still valid
				if ($validUntil->gt(Carbon::now()))
				{
					$authToken->touch();
					Visitor::current()->session->save();
					Visitor::current()->session->setId($authToken->session_id);
					Visitor::current()->session->start();
				}
				//Otherwise save new session-id
				else
				{
					$authToken->session_id = Visitor::current()->session->getId();
					$authToken->save();
				}
			}
		}

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
		if (!$once)
		{
			app('auth')->login($user);
		}

		$this->loggedInUser = $user;
		Visitor::current()->user = $user;

		return true;
	}

	/**
	 * Save the login attempt
	 * @param string $type
	 * @param string $email
	 * @param string $password
	 */
	protected function saveLoginAttempt($type, $email = null, $password = null)
	{
		$loginAttempt = new LoginAttempt();

		$loginAttempt->ip = Request::current()->ip;
		$loginAttempt->type = $type;
		$loginAttempt->email = $email;
		$loginAttempt->password = $password;
		if ($this->loggedInUser)
		{
			$loginAttempt->user()->associate($this->loggedInUser);
		}

		$loginAttempt->save();
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

	/**
	 * Get a password reset token
	 * @param string $email
	 * @return null|string
	 */
	public function getPasswordResetToken($email)
	{
		$passwordReset = new PasswordReset();
		$passwordReset->email = $email;

		if ($passwordReset->save())
		{
			return $passwordReset->token;
		}
		return null;
	}

}
