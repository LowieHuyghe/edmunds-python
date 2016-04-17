<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Events;

use Edmunds\Bases\Events\BaseBroadcastEvent;

/**
 * Broadcast to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */
class BroadcastEvent extends BaseBroadcastEvent
{
	/**
	 * @var array
	 */
	protected $channels;

	/**
	 * @var array
	 */
	protected $payload;

	/**
	 * @var string
	 */
	protected $queue;

	/**
	 * @var string
	 */
	protected $eventName;

	/**
	 * Constructor
	 * @param array $channels
	 * @param array $payload
	 */
	public function __construct($channels = [], $payload = [], $queue = Queue::QUEUE_DEFAULT, $eventName = self::class)
	{
		$this->channels = $channels;
		$this->payload = $payload;
		$this->queue = $queue;
		$this->eventName = $eventName;
	}

	/**
	 * Broadcast the event
	 */
	public function broadcast()
	{
		event($this);
	}

	/**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return $this->channels;
    }

	/**
	 * Get the data to broadcast.
	 *
	 * @return array
	 */
	public function broadcastWith()
	{
	    return $this->payload;
	}

	/**
	 * Set the name of the queue the event should be placed on.
	 *
	 * @return string
	 */
	public function onQueue()
	{
	    return $this->queue;
	}

	/**
	 * Get the broadcast event name.
	 *
	 * @return string
	 */
	public function broadcastAs()
	{
	    return $this->eventName;
	}
}
