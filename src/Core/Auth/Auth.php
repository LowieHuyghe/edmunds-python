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

use Core\Bases\Structures\BaseStructure;
use Core\Http\Request;
use Core\Localization\DateTime;
use Core\Models\Auth\AuthToken;
use Core\Models\Auth\LoginAttempt;
use Core\Models\Auth\PasswordReset;
use Core\Models\User;
use Illuminate\Auth\AuthManager;
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
	}

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
		return app('auth')->user();
	}

	/**
	 * Get the number of attempts the user has made
	 * @return int
	 */
	protected function getLoginAttemptsAttribute()
	{
		$ip = $this->request->ip;
		$dateTimeFrom = DateTime::now()->addHours(-7)->__toString();

		return LoginAttempt::where('ip', '=', $ip)->where('created_at', '>', $dateTimeFrom)->count();
	}

	/**
	 * Log a user in
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @return bool|string
	 */
	public function login($email, $password, $once = false)
	{
		$credentials = array('email' => $email, 'password' => $password);

		if ($once)
		{
			$response = app('auth')->once($credentials);
		}
		else
		{
			$response = app('auth')->attempt($credentials);
		}

		//Log attempt
		$this->saveLoginAttempt('credentials', $email, $password);

		//Return result
		return $response;
	}

	/**
	 * Log the user in with credentials
	 * @param string $email
	 * @param string $password
	 * @param bool $once
	 * @return bool
	 */
	protected function loginWeb($email, $password, $once = false)
	{
	}

	/**
	 * Get the user provider
	 * @return UserProvider
	 */
	protected function getUserProvider()
	{
		if (!isset($this->provider))
		{
			$this->provider = app('auth')->createUserProvider(config('auth.guards.' . app('auth')->getDefaultDriver() . '.provider'));
		}
		return $this->provider;
	}

	/**
	 * Log a user in
	 * @param User $user
	 */
	public function setUser($user)
	{
		app('auth')->setUser($user);
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

		$loginAttempt->ip = $this->request->ip;
		$loginAttempt->type = $type;
		$loginAttempt->email = $email;
		$loginAttempt->password = $password;
		if ($user = $this->user)
		{
			$loginAttempt->user()->associate($user);
		}

		$loginAttempt->save();
	}

	/**
	 * Log the current user out
	 */
	public function logout()
	{
		app('auth')->logout();
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
