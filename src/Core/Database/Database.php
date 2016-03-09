<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Database;

use Core\Bases\Structures\BaseStructure;
use Illuminate\Database\Query\Builder;

/**
 * The db to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Database extends BaseStructure
{
	/**
	 * The default store to load from cache
	 * @var string
	 */
	private $connection;

	/**
	 * Constructor
	 * @param string $driver
	 */
	public function __construct($driver = null)
	{
		parent::__construct();

		$this->connection = $driver;
	}

	/**
	 * @param string $query
	 * @param array $assigns
	 * @return bool|array
	 * @throws \Exception
	 */
	public function query($query, $assigns = array())
	{
		//Fetch all keys and replace with ?
		$keys = array();
		$query = preg_replace_callback('@\{(\w+)\}@', function($match) use (&$keys) {
			$keys[] = $match[1];
			return '?';
		}, $query);

		//Get all assigns in right order
		$processedAssigns = array();
		foreach ($keys as $key)
		{
			if (isset($assigns[$key]))
			{
				$processedAssigns[] = $assigns[$key];
			}
			else
			{
				throw new \Exception("Query-assign not present: $key");
			}
		}

		//Process query and run right query
		$type = array();
		preg_match('@^(\w+).*$@', trim($query), $type);
		$type = strtolower($type[1]);

		//Run query
		if ($type == 'select')
		{
			$response = app('db')->connection($this->connection)->select($query, $processedAssigns);
		}
		elseif ($type == 'insert')
		{
			$response = app('db')->connection($this->connection)->insert($query, $processedAssigns);
		}
		elseif ($type == 'update')
		{
			$response = app('db')->connection($this->connection)->update($query, $processedAssigns);
		}
		elseif ($type == 'delete')
		{
			$response = app('db')->connection($this->connection)->delete($query, $processedAssigns);
		}
		else
		{
			$response = app('db')->connection($this->connection)->statement($query, $processedAssigns);
		}

		return $response;
	}

	/**
	 * Commit a database transaction
	 * @param string $tableName
	 * @return Builder
	 */
	public function builder($tableName)
	{
		return app('db')->connection($this->connection)->table($tableName);
	}

	/**
	 * Do a transaction
	 * @param callable $callable
	 * @return mixed
	 */
	public function transaction($callable)
	{
		return app('db')->connection($this->connection)->transaction(function() use ($callable)
		{
			return call_user_func($callable, $this);
		});
	}

	/**
	 * Add listener for database stuff
	 * @param callable $callable function($sql, $bindings, $time, $connection)
	 */
	public function listen($callable)
	{
		app('db')->connection($this->connection)->listen(function($sql, $bindings, $time) use ($callable)
		{
			return call_user_func($callable, $sql, $bindings, $time, $this->connection);
		});
	}

}
