<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Events;

use Edmunds\Bases\Events\BaseBroadcastEvent;

/**
 * Broadcast to use
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
