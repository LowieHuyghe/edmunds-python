<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Localization\Commands;

use Edmunds\Bases\Commands\BaseCommand;
use Edmunds\Localization\Models\Location;
use Edmunds\Registry\Admin\Channel;
use Edmunds\Registry;

/**
 * The command for updating the geoip-databases. Please shedule for every week.
 */
class UpdateGeoIPCommand extends BaseCommand
{
	/**
	 * The files to download
	 * @var array
	 */
	private $files = array(
		Location::GEOIP_CITY => 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
	);

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'updategeoip';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the GeoIP-databases';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		foreach ($this->files as $name => $url)
		{
			$this->info("Started updating database '$name'");
			$this->info("Fetching database");

			$contents = file_get_contents($url);
			$tmpFileNameGz = tempnam(sys_get_temp_dir(), $name);
			$tmpFileNameMmdb = tempnam(sys_get_temp_dir(), $name);
			$directoryName = storage_path(Location::GEOIP_DIR);
			$fileName = "$directoryName/$name";
			$save = file_put_contents($tmpFileNameGz, $contents);

			if ($save)
			{
				$this->info("Processing database");

				$this->unzipGz($tmpFileNameGz, $tmpFileNameMmdb);
				unlink($tmpFileNameGz);

				//Check if uncompressed
				if ( ! file_exists($tmpFileNameMmdb))
				{
					continue;
				}

				//Check if geo-directory exists and make it if not
				if ( ! file_exists($directoryName))
				{
					if ( ! mkdir($directoryName, 0777, true))
					{
						$this->informAdminError("Could not create the directory for the db. ($name)");
						continue;
					}
				}

				//Check if already a db
				$oldFile = file_exists($fileName);
				if ($oldFile)
				{
					if (file_exists($fileName . '.old'))
					{
						//If already exist *.old file, remove it
						if ( ! unlink($fileName . '.old'))
						{
							$this->informAdminError("Could not remove an existing *.old file. ($name)");
							continue;
						}
					}
					//Rename to *.old
					if ( ! rename($fileName, $fileName . '.old'))
					{
						//Not succeeded and if file does not exist anymore, inform admin
						if ( ! file_exists($fileName))
						{
							$this->informAdminError("Could not rename existing db to *.old, and now there is no db anymore. ($name)");
						}
						continue;
					}
				}

				//Move new db to right location
				if (rename($tmpFileNameMmdb, $fileName))
				{
					if ($oldFile)
					{
						//Remove old file
						unlink($fileName . '.old');
					}
				}
				else
				{
					if ($oldFile)
					{
						//Error, so rename old file to correct name
						if ( ! rename($fileName . '.old', $fileName))
						{
							//If not succeeded, there is an error
							$this->informAdminError("Could not move the new db and could not move old db back. ($name)");
						}
					}
					else
					{
						//There is no geo-db
						$this->informAdminError("There is no geo-db. ($name)");
					}
					unlink($tmpFileNameMmdb);
				}

				$this->info("Succesfully updated");
			}
			else
			{
				$this->error("Couldn't download database '$name' from: $url");

				unlink($tmpFileNameGz);
				unlink($tmpFileNameMmdb);
			}
		}
	}

	/**
	 * Unzip the gz-file
	 * @param string $tmpFileName
	 * @param string $fileName
	 */
	private function unzipGz($tmpFileName, $fileName)
	{
		// Raising this value may increase performance
		$buffer_size = 4096; // read 4kb at a time

		// Open our files (in binary mode)
		$file = gzopen($tmpFileName, 'rb');
		$out_file = fopen($fileName, 'wb');

		// Keep repeating until the end of the input file
		while( ! gzeof($file)) {
			// Read buffer-size bytes
			// Both fwrite and gzread and binary-safe
			fwrite($out_file, gzread($file, $buffer_size));
		}

		// Files are done, close files
		fclose($out_file);
		gzclose($file);
	}

	/**
	 * Something went wrong and the admin needs to be notified of that
	 * @param string $message
	 */
	private function informAdminError($message)
	{
		$this->error($message);

		Registry::channel()->error('GeoIP Error!', $message);
	}
}
