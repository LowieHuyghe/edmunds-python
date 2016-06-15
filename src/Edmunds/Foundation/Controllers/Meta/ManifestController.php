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
		$manifestConfig = config('app.manifest_json');

		// enabled?
		if ($manifestConfig['enabled'])
		{
			$this->response->outputType = Response::TYPE_JSON;

			$manifest = $manifestConfig['data'];

			// add short name
			if ($shortName = config('app.info.displayname.short', false))
			{
				$manifest['short_name'] = $shortName;
			}
			// add long name
			if ($longName = config('app.info.displayname.long', false))
			{
				$manifest['name'] = $longName;
			}
			// add icons
			if ($icons = config('app.info.icons', false))
			{
				$manifest['icons'] = array_map(function ($icon)
					{
						return array(
							'src' => asset($icon['src']),
							'size' => $icon['width'] . 'x' . $icon['height'],
							'type' => $icon['type'],
						);
					}, $icons);
			}

			$this->response->assign($manifest);
		}
		else
		{
			abort(404);
		}
	}
}
