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

namespace Core\Controllers;

/**
 * Controller to extend from which requires the user to log in
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class LoginRequiredController extends BaseController
{
	/**
	 * Constructor
	 * @param bool $basic Is Basic authentication
	 */
	function __construct($basic = false)
	{
		parent::__construct();

		$this->checkLogin($basic);

		$this->response->assign('__login', $this->visitor->user);
	}

	/**
	 * Check if user is logged in
	 * @param bool $basic Is Basic authentication
	 */
	private function checkLogin($basic = false)
	{
		//If user is not logged in, redirect to other page
		if (!$this->visitor->isLoggedIn())
		{
			if ($basic)
			{
				if ($email = $this->request->getServer('PHP_AUTH_USER') && $password = $this->request->getServer('PHP_AUTH_PW'))
				{
					$this->visitor->login($email, $password);
				}
				if (!$this->visitor->isLoggedIn())
				{
					$this->response->assignHeader('WWW-Authenticate', 'Basic' /*. 'realm="Comment"'*/);
					$this->response->assignContent('Invalid credentials.');
					$this->response->setStatus(401);
					$this->response->send();
				}
			}
			else
			{
				$loginRoute = config('app.routing.loginroute');
				$this->response->responseRedirect($loginRoute, null, true);
			}
		}
	}
}