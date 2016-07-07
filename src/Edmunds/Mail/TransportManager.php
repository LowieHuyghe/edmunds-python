<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Mail;

use Edmunds\Mail\Transports\GAEMailApiTransport;
use Illuminate\Mail\TransportManager as BaseTransportManager;


/**
 * The transport manager for the mail drivers
 */
class TransportManager extends BaseTransportManager
{
	/**
     * Create an instance of the Google App Engine Mail Api Transport driver.
     *
     * @return GAEMailApiTransport
     */
    protected function createGaemailapiDriver()
    {
        $command = $this->app['config']['mail']['sendmail'];

        return GAEMailApiTransport::newInstance($command);
    }
}