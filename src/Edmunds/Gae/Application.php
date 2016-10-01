<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae;

use Edmunds\Application as EdmundsApplication;
use Edmunds\Gae\Foundation\Concerns\BindingRegisterers;
use Edmunds\Gae\Foundation\Concerns\RegistersExceptionHandlers;
use Edmunds\Gae\Foundation\Concerns\RuntimeEnvironment;
use Edmunds\Http\Request;

/**
 * The structure for application in Google App Engine
 */
class Application extends EdmundsApplication
{
	use BindingRegisterers;
	use RegistersExceptionHandlers;
	use RuntimeEnvironment;

	/**
	 * Google App Engine Identity Service
	 * @var string
	 */
	protected $gaeAppIdentityService = 'google\appengine\api\app_identity\AppIdentityService';

	/**
	 * The GAE app ID.
	 *
	 * @var string
	 */
	protected $gaeAppId;

	/**
	 * Get the default bucket path to use as storage
	 * @var string
	 */
	protected $gaeBucketPath;

	/**
	 * Returns the GAE app ID.
	 *
	 * @return string
	 */
	public function getAppId()
	{
		if ( ! isset($this->gaeAppId))
		{
			$appIdentityService = $this->appIdentityService;
			$this->gaeAppId = $appIdentityService::getApplicationId();
		}

		return $this->gaeAppId;
	}

	/**
	 * Determine if the application is running in Google App Engine console.
	 *
	 * @return bool
	 */
	public function runningInConsole()
	{
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
	public function storagePath($path = null)
	{
		if ( ! isset($this->gaeBucketPath))
		{
			// Get the first bucket in the list.
			$buckets = ini_get('google_app_engine.allow_include_gs_buckets');
			$bucket = current(explode(', ', $buckets));

			if ($bucket)
			{
				$this->gaeBucketPath = "gs://{$bucket}";

				if (env('GAE_SKIP_GCS_INIT') !== true
					&& ! file_exists($this->gaeBucketPath))
				{
					mkdir($this->gaeBucketPath);
					mkdir($this->gaeBucketPath . '/app');
					mkdir($this->gaeBucketPath . '/framework');
					mkdir($this->gaeBucketPath . '/framework/views');
				}
			}
		}

		return $this->gaeBucketPath . '/storage' . ($path ? '/' . $path : $path);
	}
}