<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Concerns;

use Edmunds\Http\Request;
use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;


/**
 * The GoogleAppEngine concern
 */
trait GoogleAppEngine
{
	/**
	 * 'true' if running on GAE.
	 *
	 * @var bool
	 */
	protected $gaeEnvironment;

	/**
	 * The GAE app ID.
	 *
	 * @var string
	 */
	protected $gaeAppId;

	/**
	 * Check if running in production
	 *
	 * @var bool
	 */
	protected $gaeProduction;

	/**
	 * Get the default bucket path to use as storage
	 * @var string
	 */
	protected $gaeBucketPath;

	/**
	 * Initiate stuff related to Google App Engine
	 *
	 * @return void
	 */
	protected function initGoogleAppEngine()
	{
		$this->detectGae();

		if ($this->isGae())
		{
			$this->initializeGaeBucket();
		}
	}

	/**
	 * Detect if the application is running on GAE.
	 */
	protected function detectGae()
	{
		$appIdentityService = 'google\appengine\api\app_identity\AppIdentityService';

		if ( ! class_exists($appIdentityService))
		{
			$this->gaeEnvironment = false;
			return;
		}

		$this->gaeEnvironment = (bool) $appIdentityService::getDefaultVersionHostname();
		if ($this->gaeEnvironment)
		{
			$this->gaeAppId = $appIdentityService::getApplicationId();
			$this->gaeProduction = ! preg_match('/dev~/', getenv('APPLICATION_ID'));
		}
	}

	/**
	 * Initializes the GCS Bucket.
	 */
	protected function initializeGaeBucket()
	{
		if ( ! is_null($this->gaeBucketPath))
		{
			return;
		}

		// Get the first bucket in the list.
		$buckets = ini_get('google_app_engine.allow_include_gs_buckets');
		$bucket = current(explode(', ', $buckets));

		if ($bucket)
		{
			$this->gaeBucketPath = "gs://{$bucket}";

			if (env('GAE_SKIP_GCS_INIT') === true)
			{
				return $this->gaeBucketPath;
			}

			if ( ! file_exists($this->gaeBucketPath))
			{
				mkdir($this->gaeBucketPath);
				mkdir($this->gaeBucketPath . '/app');
				mkdir($this->gaeBucketPath . '/framework');
				mkdir($this->gaeBucketPath . '/framework/views');
			}
		}
	}


	/**
	 * Returns 'true' if running on GAE.
	 *
	 * @return bool
	 */
	public function isGae()
	{
		return $this->gaeEnvironment;
	}

	/**
	 * Returns the GAE app ID.
	 *
	 * @return string
	 */
	public function getGaeAppId()
	{
		return $this->gaeAppId;
	}

	/**
	 * Get or check the current Google App Engine application environment.
	 *
	 * @param  mixed
	 * @return string
	 */
	protected function gaeEnvironment()
	{
		$env = $this->gaeProduction ? 'production' : 'develop';

		if (func_num_args() > 0)
		{
			$patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

			foreach ($patterns as $pattern)
			{
				if (Str::is($pattern, $env))
				{
					return true;
				}
			}

			return false;
		}

		return $env;
	}

	/**
	 * Determine if the application is running in Google App Engine console.
	 *
	 * @return bool
	 */
	public function runninginGaeConsole()
	{
		if ( ! $this->isGae())
		{
			return false;
		}

		$request = Request::getInstance();

		$cronHeader = $request->getHeader('X-AppEngine-Cron');
		$queueHeader = $request->getHeader('X-AppEngine-QueueName');

		return ( $cronHeader || $queueHeader );
	}

	/**
	 * Get the storage path for the application.
	 *
	 * @param  string|null  $path
	 * @return string
	 */
	protected function gaeStoragePath($path = null)
	{
		return $this->gaeBucketPath . '/storage' . ($path ? '/' . $path : $path);
	}

	/**
	 * Get the Google App Engine Monolog handler for the application.
	 *
	 * @return \Monolog\Handler\AbstractHandler
	 */
	protected function getGaeMonologHandler()
	{
		return (new SyslogHandler(
			null,
			LOG_USER,
			Logger::DEBUG))
				->setFormatter(new LineFormatter(null, null, true, true));
	}
}