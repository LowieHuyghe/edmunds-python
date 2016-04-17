<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Controllers;

use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Http\Response;

/**
 * Controller that renders a requested view
 * (Mainly meant for requesting component-templates for in example Angular)
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