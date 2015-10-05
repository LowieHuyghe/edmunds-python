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

namespace Core\Jobs;
use Illuminate\Http\Request;

/**
 * The command for updating the geoip-databases. Please shedule for every week.
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class LogQueue extends BaseQueue
{
	/**
	 * Url to call
	 * @var string
	 */
	protected $url;

	/**
	 * Constructor
	 * @param string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		file_get_contents($this->url);
	}
}
