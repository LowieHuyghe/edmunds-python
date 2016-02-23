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
		// Password Reset Routes...
		$app->get($prefix . 'password/reset/{token?}', '\\' . get_called_class() . '@showResetForm');
		$app->post($prefix . 'password/email', '\\' . get_called_class() . '@sendResetLinkEmail');
		$app->post($prefix . 'password/reset', '\\' . get_called_class() . '@reset');
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
		$this->middleware('guest');
	}

	/**
	 * Add validation rules
	 */
	protected function addValidationRules()
	{
		$this->validator->value('token')->max(255)->setRequired();
		$this->validator->value('email')->max(255)->email()->setRequired();
		$this->validator->value('password')->min(6)->max(60)->setRequired();
	}
}