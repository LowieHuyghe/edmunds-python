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
use Illuminate\Support\Debug\HtmlDumper;
use Illuminate\Support\Str;


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
			$this->replaceDefaultSymfonyLineDumpers();
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
	 * Replaces the default output stream of Symfony's
	 * CliDumper and HtmlDumper classes in order to
	 * be able to run on Google App Engine.
	 *
	 * 'php://stdout' is used by CliDumper,
	 * 'php://output' is used by HtmlDumper,
	 * both are not supported on GAE.
	 */
	protected function replaceDefaultSymfonyLineDumpers()
	{
		HtmlDumper::$defaultOutput =
		// CliDumper::$defaultOutput =
			function($line, $depth, $indentPad)
			{
				if (-1 !== $depth)
				{
					echo str_repeat($indentPad, $depth) . $line . "\n";
				}
			};
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
	 * Get or check the current application environment.
	 *
	 * @param  mixed
	 * @return string
	 */
	public function environment()
	{
		if ($this->isGae())
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
		else
		{
			return call_user_func_array(array($this, 'parent::' . __FUNCTION__), func_get_args());
		}
	}

	/**
	 * Determine if the application is running in the console.
	 *
	 * @return bool
	 */
	public function runningInConsole()
	{
		if ($this->isGae())
		{
			$cronHeader = Request::getInstance()->getHeader('X-AppEngine-Cron');

			return $cronHeader && ( $cronHeader === 'true' || $cronHeader === true );
		}
		else
		{
			return parent::runningInConsole();
		}
	}

	/**
	 * Get the storage path for the application.
	 *
	 * @param  string|null  $path
	 * @return string
	 */
	public function storagePath($path = null)
	{
		if ($this->isGae() && $this->gaeBucketPath)
		{
			return $this->gaeBucketPath . '/storage' . ($path ? '/' . $path : $path);
		}
		else
		{
			return parent::storagePath($path);
		}
	}
}