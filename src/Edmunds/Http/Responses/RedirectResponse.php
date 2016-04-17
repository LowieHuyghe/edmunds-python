<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Http\Responses;

use Edmunds\Bases\Responses\BaseResponse;
use Edmunds\Http\Responses\ViewResponse;

/**
 * A redirect response
 *
 * @property string $uri
 * @property bool $saveIntended
 * @property bool $gotoIntended
 */
class RedirectResponse extends BaseResponse
{
	/**
	 * Constructor
	 * @param string $uri
	 */
	public function __construct($uri, $saveIntended, $gotoIntended)
	{
		parent::__construct();

		$this->uri = $uri;
		$this->saveIntended = $saveIntended;
		$this->gotoIntended = $gotoIntended;
	}

	/**
	 * Get the response
	 * @param array $data
	 * @return \Illuminate\Http\Response
	 */
	public function getResponse($data = array())
	{
		$data = $this->processData($data);

		$redirector = redirect();
		$stateful = app()->isStateful();

		//Make the redirect-response
		if (is_null($this->uri))
		{
			$response = $redirector->back();
		}
		elseif ($stateful && $this->gotoIntended)
		{
			$response = $redirector->intended($this->uri);
		}
		elseif ($stateful && $this->saveIntended)
		{
			$response = $redirector->guest($this->uri);
		}
		else
		{
			$response = $redirector->to($this->uri);
		}

		// assign data
		if (app()->isStateful())
		{
			$response->with($data);
		}

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
		view()->addNamespace('edmunds', EDMUNDS_BASE_PATH . '/resources/views');
		$response = new ViewResponse();
		$response->addView(null, 'edmunds::redirect');
		$response = $response->getResponse(array('targetUrl' => $targetUrl, 'debugTrace' => join('<br/>', $debugTrace)));

		return $response;
	}
}
