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

namespace Core\Foundation\Controllers\Auth;

use Core\Auth\Auth;
use Core\Auth\Concerns\AuthenticatesAndRegistersUsers;
use Core\Auth\Concerns\ThrottlesLogins;
use Core\Bases\Http\Controllers\BaseController;

/**
 * Controller that handles authentication
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class AuthController extends BaseController
{
	/**
	 * Register the default routes for this controller
	 * @param  Application $app
	 * @param  string $prefix
	 * @param  array  $middleware
	 */
	public static function registerRoutes(&$app, $prefix ='', $middleware = array())
	{
		// Authentication Routes...
		$app->get($prefix . 'login', '\\' . get_called_class() . '@showLoginForm');
		$app->post($prefix . 'login', '\\' . get_called_class() .  '@login');
		$app->get($prefix . 'logout', '\\' . get_called_class() .  '@logout');

		// Registration Routes...
		$app->get($prefix . 'register', '\\' . get_called_class() .  '@showRegistrationForm');
		$app->post($prefix . 'register', '\\' . get_called_class() .  '@register');
	}

	/**
	 * Authentication instance
	 * @var Auth
	 */
	protected $auth;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth = Auth::getInstance();
		$this->middleware('guest', ['except' => 'logout']);
	}

	/**
	 * Show login form
	 */
	public function showLoginForm()
	{
		$this->response
			->assign('attemptsTooMany', $this->auth->attemptsTooMany)
			->render(null, 'auth.login');
	}

	/**
	 * Login
	 */
	public function login()
	{
		// add validation rules
		$this->addValidationRules(false);

		// check if error
		if ($this->validator->hasErrors())
		{
			$this->response
				->assignErrors($this->validator->getErrors())
				->assignInput('password')
				->redirect(null);
		}

		// login user
		else
		{
			$this->auth->login(
				$this->validator->get('email'),
				$this->validator->get('password'),
				false,
				$this->validator->get('remember'));

			// redirect
			$this->response->redirect(config('app.auth.redirects.login'));
		}
	}

	/**
	 * Logout
	 */
	public function logout()
	{
		// logout user
		$this->auth->logout();

		// redirect
		$this->response->redirect(config('app.auth.redirects.logout'));
	}

	/**
	 * Show registration form
	 */
	public function showRegistrationForm()
	{
		$this->response->render(null, 'auth.register');
	}

	/**
	 * Register
	 */
	public function register()
	{
		// add validation rules
		$this->addValidationRules(true);

		// check if error
		if ($this->validator->hasErrors())
		{
			$this->response
				->assignErrors($this->validator->getErrors())
				->assignInput('password')
				->redirect(null);
		}

		// create and login
		else
		{
			$this->auth->loginUser($this->create(), $this->validator->get('remember'));

			// redirect
			$this->response->redirect(config('app.auth.redirects.login'));
		}
	}

	/**
	 * Add validation rules
	 * @param bool $register Check if register
	 */
	protected function addValidationRules($register)
	{
		$this->validator->value('email')->max(255)->email()->setRequired();
		$this->validator->value('password')->max(60)->setRequired();
		$this->validator->value('remember')->boolean()->fallback(false);

		if (!$register)
		{
			$this->validator->value('email')->unique('users');
		}
	}

	/**
	 * Create a new user instance after a valid registration.
	 * @return User
	 */
	protected function create()
	{
		return ${config('app.auth.models.user')}::create(array(
			'email' => $this->validator->get('email'),
			'password' => bcrypt($this->validator->get('password')),
		));
	}
}