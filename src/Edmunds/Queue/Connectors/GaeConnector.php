<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Queue\Connectors;

use Edmunds\Queue\Queues\GaeQueue;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Http\Request;
use Illuminate\Queue\Connectors\ConnectorInterface;


/**
 * Google App Engine Connector
 */
class GaeConnector implements ConnectorInterface
{
	/**
	 * The encrypter instance.
	 *
	 * @var \Illuminate\Encryption\Encrypter
	 */
	protected $crypt;

	/**
	 * The current request instance.
	 *
	 * @var \Illuminate\Http\Request;
	 */
	protected $request;

	/**
	 * Create a new GAE connector instance.
	 *
	 * @param \Illuminate\Contracts\Encryption\Encrypter $crypt
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct(EncrypterContract $crypt, Request $request)
	{
		$this->crypt = $crypt;
		$this->request = $request;
	}

	/**
	 * Establish a queue connection.
	 *
	 * @param  array  $config
	 * @return \Illuminate\Queue\QueueInterface
	 */
	public function connect(array $config)
	{
		return new GaeQueue($this->request, $config['queue'], $config['url'], $config['encrypt']);
	}
}