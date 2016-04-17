<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Edmunds\Foundation\Controllers;

use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Http\Response;

/**
 * Controller that renders a requested view
 * (Mainly meant for requesting component-templates for in example Angular)
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class ViewController extends BaseController
{
	/**
	 * Register the default routes for this controller
	 * @param  Application $app
	 * @param  string $prefix
	 * @param  array  $middleware
	 */
	public static function registerRoutes(&$app, $prefix ='', $middleware = array())
	{
		$app->get($prefix . 'view', '\\' . get_called_class() . '@getView');
	}

	/**
	 * The templates allowed to be rendered
	 * @var array
	 */
	protected $only = array(
		//
	);

	/**
	 * Render the view
	 */
	public function getView()
	{
		$this->input->rule('view')->required()->in($this->only);

		// not valid
		if ($this->input->hasErrors())
		{
			abort(403);
		}

		// assign view
		else
		{
			$this->response
				->assign($this->input->except('view'))
				->view(null, $this->input->get('view'));
		}
	}
}