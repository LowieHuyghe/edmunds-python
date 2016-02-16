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

namespace Core\Http\Client;

use Core\Bases\Structures\BaseStructure;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

/**
 * The helper for the session
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Session extends BaseStructure implements SessionInterface
{
	/**
	 * Instance of the Session-structure
	 * @var Request
	 */
	private static $instance;

	/**
	 * Fetch instance of the Session-structure
	 * @return Request
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Session(app('session.store'));
		}

		return self::$instance;
	}

	/**
	 * @var SessionInterface
	 */
	private $session;

	/**
	 * Constructor
	 * @param SessionInterface $session
	 */
	public function __construct($session)
	{
		parent::__construct();

		$this->session = $session;
	}

	/**
	 * Checks if an attribute is defined.
	 * @param string $name The attribute name
	 * @return bool true if the attribute is defined, false otherwise
	 */
	public function has($name)
	{
		return $this->session->has($name);
	}

	/**
	 * Sets an attribute.
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value)
	{
		$this->session->set($name, $value);
	}

	/**
	 * Returns an attribute.
	 * @param string $name    The attribute name
	 * @param mixed  $default The default value if not found.
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		return $this->session->get($name, $default);
	}

	/**
	 * Removes an attribute.
	 * @param string $name
	 * @return mixed The removed value or null when it does not exist
	 */
	public function remove($name)
	{
		return $this->session->remove($name);
	}

	/**
	 * Starts the session storage.
	 * @return bool True if session started.
	 * @throws \RuntimeException If session fails to start.
	 * @api
	 */
	public function start()
	{
		return $this->session->start();
	}

	/**
	 * Returns the session ID.
	 * @return string The session ID.
	 * @api
	 */
	public function getId()
	{
		return $this->session->getId();
	}

	/**
	 * Sets the session ID.
	 * @param string $id
	 * @api
	 */
	public function setId($id)
	{
		$this->session->setId($id);
	}

	/**
	 * Returns the session name.
	 * @return mixed The session name.
	 * @api
	 */
	public function getName()
	{
		return $this->session->getName();
	}

	/**
	 * Sets the session name.
	 * @param string $name
	 * @api
	 */
	public function setName($name)
	{
		$this->session->setName($name);
	}

	/**
	 * Invalidates the current session.
	 * Clears all session attributes and flashes and regenerates the
	 * session and deletes the old session from persistence.
	 * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
	 *                      will leave the system settings unchanged, 0 sets the cookie
	 *                      to expire with browser session. Time is in seconds, and is
	 *                      not a Unix timestamp.
	 *
	 * @return bool True if session invalidated, false if error.
	 * @api
	 */
	public function invalidate($lifetime = null)
	{
		return $this->session->invalidate($lifetime);
	}

	/**
	 * Migrates the current session to a new session id while maintaining all
	 * session attributes.
	 * @param bool $destroy Whether to delete the old session or leave it to garbage collection.
	 * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
	 *                       will leave the system settings unchanged, 0 sets the cookie
	 *                       to expire with browser session. Time is in seconds, and is
	 *                       not a Unix timestamp.
	 * @return bool True if session migrated, false if error.
	 * @api
	 */
	public function migrate($destroy = false, $lifetime = null)
	{
		return $this->session->migrate($destroy, $lifetime);
	}

	/**
	 * Force the session to be saved and closed.
	 * This method is generally not required for real sessions as
	 * the session will be automatically saved at the end of
	 * code execution.
	 */
	public function save()
	{
		$this->session->save();
	}

	/**
	 * Returns attributes.
	 * @return array Attributes
	 * @api
	 */
	public function all()
	{
		return $this->session->all();
	}

	/**
	 * Sets attributes.
	 * @param array $attributes Attributes
	 */
	public function replace(array $attributes)
	{
		$this->session->replace($attributes);
	}

	/**
	 * Clears all attributes.
	 * @api
	 */
	public function clear()
	{
		$this->session->clear();
	}

	/**
	 * Checks if the session was started.
	 * @return bool
	 */
	public function isStarted()
	{
		return $this->session->isStarted();
	}

	/**
	 * Registers a SessionBagInterface with the session.
	 * @param SessionBagInterface $bag
	 */
	public function registerBag(SessionBagInterface $bag)
	{
		$this->session->registerBag($bag);
	}

	/**
	 * Gets a bag instance by name.
	 * @param string $name
	 * @return SessionBagInterface
	 */
	public function getBag($name)
	{
		return $this->session->getBag($name);
	}

	/**
	 * Gets session meta.
	 * @return MetadataBag
	 */
	public function getMetadataBag()
	{
		return $this->session->getMetadataBag();
	}

	/**
	 * Gets the session token.
	 * @return string
	 */
	public function token()
	{
		return $this->session->token();
	}
}