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

namespace Core\Analytics;

use SobanVuex\NewRelic\Agent;

/**
 * The New Relic helper
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class NewRelic extends Agent
{

	/**
	 * Instance of the NewRelic-helper
	 * @var NewRelic
	 */
	private static $instance;

	/**
	 * Initialize the request-helper
	 * @param string $appName
	 * @param string $license
	 */
	public static function initialize($appName, $license)
	{
		self::$instance = new NewRelic($appName, $license);
	}

	/**
	 * Fetch instance of the response-helper
	 * @return Request
	 */
	public static function getInstance()
	{
		return self::$instance;
	}

    /**
     * The app name
     * @var string
     */
    protected $appName;

    /**
     * License
     * @var string
     */
    protected $license;

    /**
     * @link https://docs.newrelic.com/docs/agents/php-agent/configuration/php-agent-api#api-set-appname
     *
     * @param string $name
     * @param string $license
     * @param bool   $xmit
     *
     * @return bool
     */
    public function setAppname($name, $license = null, $xmit = false)
    {
        if (!$this->isLoaded()) {
            return false;
        }

        $this->appName = $name;
        $this->license = $license;

        return newrelic_set_appname($name, $license ?: ini_get('newrelic.license'), $xmit);
    }

    /**
     * Added in v. 3.0 of the New Relic Agent.
     *
     * @link https://docs.newrelic.com/docs/agents/php-agent/configuration/php-agent-api#api-start-txn
     * @see endTransaction
     *
     * @param string $appname
     * @param string $license
     *
     * @return bool
     */
    public function startTransaction($appname = null, $license = null)
    {
    	return parent::startTransaction($appname ?: $this->appName, $license ?: $this->license);
    }

}
