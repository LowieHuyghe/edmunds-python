<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Events;

use Edmunds\Bases\Events\BaseEvent;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Broadcast base to extend from
 */
class BaseBroadcastEvent extends BaseEvent implements ShouldBroadcast
{
	//
}
