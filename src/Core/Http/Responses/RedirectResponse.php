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
 * @property mixed $input
 */
class RedirectResponse extends BaseResponse
{
	/**
	 * Constructor
	 * @param string $uri
	 * @param mixed $input
	 */
	public function __construct($uri, $input = null)
	{
		parent::__construct();

		$this->uri = $uri;
		$this->input = $input;
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

		//Assign input
		if ($this->input === true)
		{
			$response = $response->withInput();
		}
		else
		{
			$response = $response->withInput($this->input);
		}

		//For debugging purposes show the redirect-page
		if (app()->isLocal() && config('routing.redirecthalt', false))
		{
			//Fetch the debugtrace
			ob_start();
			debug_print_backtrace();
			$debugPrintBacktrace = ob_get_contents();
			ob_end_clean();

			$response = $this->viewRedirect($response, $debugPrintBacktrace);
		}

		//Return response
		return $response;
	}

	/**
	 * Show the redirect page
	 * @param $response
	 * @param string $debugPrintBacktrace
	 * @return string
	 */
	private function viewRedirect($response, $debugPrintBacktrace)
	{
		//Format target-url
		$targetUrl = parse_url($response->getTargetUrl(), PHP_URL_PATH);
		if (empty($targetUrl))
		{
			$targetUrl = '/';
		}

		//Format backtrace
		$debugPrintBacktrace = explode("\n", $debugPrintBacktrace);
		$debugTrace = array();
		foreach ($debugPrintBacktrace as $line)
		{
			if (preg_match("@^#\d+.*?[Ii]lluminate@", $line))
			{
				break;
			}
			$line = str_replace(base_path(), '', $line);
			$line = preg_replace("@^(#\d+)@", "<span class='tracenumber'>$1</span>", $line);
			$debugTrace[] = $line;
		}
		$debugTrace = join('<br/>', $debugTrace);

		//Redender view
		view()->addNamespace('core', CORE_BASE_PATH . '/resources/views');
		$response = new ViewResponse();
		$response->addView(null, 'core::redirect');
		$response = $response->getResponse(array('targetUrl' => $targetUrl, 'debugTrace' => $debugTrace));

		return $response;
	}
}
