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

namespace LH\Core\Exceptions;

/**
 * Exception to indicate Configuration is short
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ConfigNotFoundException extends BaseException
{

	/**
	 * Constructor
	 * @param string|array $config
	 */
	function __construct($config)
	{
		if (is_array($config))
		{
			if (count($config) > 1)
			{
				$message = implode(', ', $config);
			}
			else
			{
				$message = $config[0];
			}
		}
		else
		{
			$message = $config;
		}

		parent::__construct("$message not found in Config");
	}

}