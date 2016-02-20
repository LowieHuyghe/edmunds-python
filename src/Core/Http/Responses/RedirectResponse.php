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

namespace Core\Http\Responses;

use Core\Bases\Responses\BaseResponse;
use Core\Http\Responses\ViewResponse;

/**
 * A redirect response
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $uri
 */
class RedirectResponse extends BaseResponse
{
	/**
	 * Constructor
	 * @param string $uri
	 */
	public function __construct($uri)
	{
		parent::__construct();

		$this->uri = $uri;
	}

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		//Make the redirect-response
		$response = redirect($this->uri);

		//For debugging purposes show the redirect-page
		if (app()->isLocal() && config('app.routing.redirecthalt', false))
		{
			//Fetch the debugtrace
			$trace = debug_backtrace();
			$debugTrace = array();
			foreach ($trace as $line)
			{
				if (isset($line['file']))
				{
					$debugTrace[] = str_replace(base_path(), '', $line['file']) . ':' . $line['line'] . ' ~ ' . $line['function'];
				}
			}

			$response = $this->viewRedirect($response, $debugTrace);
		}

		//Return response
		return $response;
	}

	/**
	 * Show the redirect page
	 * @param $response
	 * @param string $debugTrace
	 * @return string
	 */
	private function viewRedirect($response, $debugTrace)
	{
		//Format target-url
		$targetUrl = parse_url($response->getTargetUrl(), PHP_URL_PATH);
		if (empty($targetUrl))
		{
			$targetUrl = '/';
		}

		//Redender view
		view()->addNamespace('core', CORE_BASE_PATH . '/resources/views');
		$response = new ViewResponse();
		$response->addView(null, 'core::redirect');
		$response = $response->getResponse(array('targetUrl' => $targetUrl, 'debugTrace' => join('<br/>', $debugTrace)));

		return $response;
	}
}
