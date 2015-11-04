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

namespace Core\Analytics\Logging;

use Core\Bases\Structures\Analytics\BaseReport;

/**
 * The structure for timing reports
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
	//Timing
 * @property string $userTimingCategory
 * @property string $userTimingVariableName
 * @property int $userTimingTime
 * @property string $userTimingLabel
 * @property int $pageLoadTime
 * @property int $dnsTime
 * @property int $pageDownloadTime
 * @property int $redirectResponseTime
 * @property int $tcpConnectTime
 * @property int $serverResponseTime
 * @property int $domInteractiveTime
 * @property int $contentLoadTime
 */
class TimingReport extends BaseReport
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->hitType = 'timing';
	}

}
