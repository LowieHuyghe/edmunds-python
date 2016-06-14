<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Controllers\Meta;

use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Http\Response;

/**
 * Controller that handles manifest.json
 */
class ManifestController extends BaseController
{
	/**
	 * Get manifest.json
	 */
	public function getJson()
	{
		$manifest = config('app.manifest_json');

		// enabled?
		if ($manifest['enabled'])
		{
			$this->response->outputType = Response::TYPE_JSON;

			$this->response->assign($manifest['data']);
		}
		else
		{
			abort(404);
		}
	}
}
