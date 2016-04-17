<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Providers;

use Edmunds\Bases\Providers\BaseEventServiceProvider;

/**
 * The event service provider
 */
class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * The event listener mappings for the application in Edmunds.
     *
     * @var array
     */
    protected $edmundsListen = [
        //
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listen = $this->edmundsListen + $this->listen;
    }
}
