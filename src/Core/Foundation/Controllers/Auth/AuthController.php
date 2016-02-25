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
		$app->get($prefix . 'login', '\\' . get_called_class() . '@getLogin');
		$app->post($prefix . 'login', '\\' . get_called_class() .  '@postLogin');
		$app->get($prefix . 'logout', '\\' . get_called_class() .  '@getLogout');

		// Registration Routes...
		$app->get($prefix . 'register', '\\' . get_called_class() .  '@getRegister');
		$app->post($prefix . 'register', '\\' . get_called_class() .  '@postRegister');
	}

	/**
	 * Authentication instance
	 * @var Auth
	 */
	protected $auth;

	/**
	 * The login view
	 * @var string
	 */
	protected $viewLogin = 'auth.login';

	/**
	 * The register view
	 * @var string
	 */
	protected $viewRegister = 'auth.register';

	/**
	 * Path to redirect to when logged in
	 * @var string
	 */
	protected $postLogin = '/';

	/**
	 * Path to redirect to when logged out
	 * @var string
	 */
	protected $postLogout = '/login';

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
	public function getLogin()
	{
		$this->response
			->assign('attemptsTooMany', $this->auth->attemptsTooMany)
			->view(null, $this->viewLogin);
	}

	/**
	 * Login
	 */
	public function postLogin()
	{
		// add validation rules
		$this->addValidationRules(false);

		// check if error
		if ($this->input->hasErrors())
		{
			$this->response
				->errors($this->input->getErrors())
				->input('password')
				->redirect(null);
		}

		// login user
		else
		{
			$this->auth->login(
				$this->input->get('email'),
				$this->input->get('password'),
				false,
				$this->input->get('remember'));

			// redirect
			$this->response->redirect($this->postLogin);
		}
	}

	/**
	 * Logout
	 */
	public function getLogout()
	{
		// logout user
		$this->auth->logout();

		// redirect
		$this->response->redirect($this->postLogout);
	}

	/**
	 * Show registration form
	 */
	public function getRegister()
	{
		$this->response->view(null, $this->viewRegister);
	}

	/**
	 * Register
	 */
	public function postRegister()
	{
		// add validation rules
		$this->addValidationRules(true);

		// check if error
		if ($this->input->hasErrors())
		{
			$this->response
				->errors($this->input->getErrors())
				->input('password')
				->redirect(null);
		}

		// create and login
		else
		{
			$this->auth->loginUser($this->create(), $this->input->get('remember'));

			// redirect
			$this->response->redirect($this->postLogin);
		}
	}

	/**
	 * Add validation rules
	 * @param bool $register Check if register
	 */
	protected function addValidationRules($register)
	{
		$this->input->rule('email')->max(255)->email()->required();
		$this->input->rule('password')->max(60)->required();
		$this->input->rule('remember')->boolean()->fallback(false);

		if (!$register)
		{
			$this->input->rule('email')->unique('users');
		}
	}

	/**
	 * Create a new user instance after a valid registration.
	 * @return User
	 */
	protected function create()
	{
		return ${config('app.auth.models.user')}::create(array(
			'email' => $this->input->get('email'),
			'password' => bcrypt($this->input->get('password')),
		));
	}
}