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
 * Controller that handles password resets
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PasswordController extends BaseController
{
	/**
	 * Register the default routes for this controller
	 * @param  Application $app
	 * @param  string $prefix
	 * @param  array  $middleware
	 */
	public static function registerRoutes(&$app, $prefix ='', $middleware = array())
	{
		// Password Reset Routes...
		$app->get($prefix . 'password/reset/{token?}', '\\' . get_called_class() . '@getReset');
		$app->post($prefix . 'password/email', '\\' . get_called_class() . '@postEmail');
		$app->post($prefix . 'password/reset', '\\' . get_called_class() . '@postReset');
	}

	/**
	 * Authentication instance
	 * @var Auth
	 */
	protected $auth;

	/**
	 * Path to redirect to when logged in
	 * @var string
	 */
	protected $postLogin = '/';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth = Auth::getInstance();
		$this->middleware('guest');
	}

	/**
	 * Display the password reset view for the given token.
	 * If no token is present, display the link request form.
	 * @param  string|null  $token
	 */
	public function getReset($token = null)
	{
		// no token was present
		if (is_null($token))
		{
			$this->response->view(null, 'auth.passwords.request');
		}

		// token is present
		else
		{
			$this->addValidationRules();
			$this->input->rule('email')->max(255)->email();
			$email = $this->input->get('email');

			$this->response
				->assign('email', $email)
				->view(null, 'auth.passwords.email');
		}
	}

	/**
	 * Send a reset link to the given user.
	 */
	public function postEmail()
	{
		$this->input->rule('email')->max(255)->email()->required();

		// has errors
		if ($this->input->hasErrors())
		{
			abort(403);
		}

		// got email
		else
		{
			// send mail
			// TODO review with new email system
			$response = app('auth.passwords')->broker(null)->sendResetLink($this->input->get('email'), function (Message $message)
			{
				$message->subject(trans('passwords.email.subject'));
			});

			if ($response == \Illuminate\Support\Facades\Password::PASSWORD_RESET)
			{
				$this->response
					->assign('status', trans($response))
					->back();
			}
			else
			{
				$this->response
					->withErrors(['email' => trans($response)])
					->back();
			}
		}
	}

	/**
	 * Reset the given user's password.
	 */
	public function postReset()
	{
		$this->input->rule('token')->max(255)->required();
		$this->input->rule('email')->max(255)->email()->required();
		$this->input->rule('password')->min(6)->max(60)->required();
		$this->input->rule('password_confirmation')->min(6)->max(60)->required();

		// there are errors
		if ($this->input->hasErrors())
		{
			abort(403);
		}

		// reset password
		else
		{
			$credentials = $this->input->only('email', 'password', 'password_confirmation', 'token');

			// reset password
			$response = app('auth.passwords')->broker(null)->reset($credentials, function ($user, $password)
			{
				$user->password = bcrypt($password);
				$user->save();

				$this->auth->loginUser($user);
			});

			// email sent
			if ($response == \Illuminate\Support\Facades\Password::PASSWORD_RESET)
			{
				$this->response
					->assign('status', trans($response))
					->redirect($this->postLogin);
			}
			// something went wrong
			else
			{
				$this->response
					->inputOnly('email')
					->errors(['email' => trans($response)])
					->back();
			}
		}
	}
}