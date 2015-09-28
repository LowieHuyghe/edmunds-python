<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Structures\Analytics;

use LH\Core\Helpers\RequestHelper;
use LH\Core\Helpers\ValidationHelper;

/**
 * The structure for page reports
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PageReport extends BaseReport
{
	/**
	 * PageReport constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->type = 'pageview';
		$this->documentHostName = RequestHelper::getInstance()->getRoot();
		$path = substr(RequestHelper::getInstance()->getFullUrl(), strlen($this->documentHostName));
		if (!$path || $path[0] != '/')
		{
			$path = '/' . $path;
		}
		$this->documentPath = $path;
	}
}
