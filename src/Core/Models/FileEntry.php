<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Models;
use Faker\Generator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * The model for files
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property string name
 * @property string md5
 * @property string sha1
 * @property string original_name
 * @property string mime
 * @property int size
 */
class FileEntry extends BaseModel
{
	/**
	 * The table to store the data
	 * @var string
	 */
	protected $table = 'file_entries';

	/**
	 * Timestamps in the table
	 * @var bool|array
	 */
	public $timestamps = true;

	/**
	 * The resource from the file
	 * @var resource
	 */
	private $resource;

	/**
	 * The path of the uplaoded file
	 * @var string
	 */
	private $uploadPath;

	/**
	 * The name of the last changed resource of the file (to save)
	 * @var string
	 */
	private $lastChanged;

	public function save()
	{

	}

	public function saveAs()
	{

	}

	public function delete()
	{

	}

	/**
	 * Get the complete path to the file
	 * @return string
	 */
	public function getFullPath()
	{
		return self::getDisk()->getDriver()->getAdapter()->getPathPrefix() . $this->name;
	}

	/**
	 * Check if saved file exists
	 * @return bool
	 */
	public function getFileExist()
	{
		return self::getDisk()->exists($this->name);
	}

	/**
	 * Check if file is image
	 * @return bool
	 */
	public function isImage()
	{
		return in_array($this->mime, array(
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
		));
	}

	/**
	 * Check if file is document
	 * @return bool
	 */
	public function isDocument()
	{
		return in_array($this->mime, array(
			'application/pdf',
			'application/msword',
		));
	}

	/**
	 * Get the resource based on the file
	 * @return resource
	 */
	public function getResource()
	{
		if (!isset($this->resource))
		{
			if ($this->getFileExist())
			{
				$this->resource = File::get($this->getFullPath());
			}
			else
			{
				$this->resource = null;
			}
		}
		return $this->resource;
	}

	/**
	 * Set the changed resource
	 * @param resource $resource
	 */
	public function setResource($resource)
	{
		$this->resource = $resource;
		$this->lastChanged = 'resource';
	}

	/**
	 * Get the disk to handle files
	 * @return mixed
	 */
	private static function getDisk()
	{
		return Storage::disk(Config::get('filesystems.default'));
	}

	/**
	 * Add the validation of the model
	 * @param ValidationHelper $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->integer('id');

		$validator->required('name');
		$validator->max('name', 20);
		$validator->unique('name', 'file_entries');

		$validator->required('md5');
		$validator->max('md5', 32);

		$validator->required('sha1');
		$validator->max('sha1', 40);

		$validator->required('original_name');
		$validator->max('original_name', 255);

		$validator->required('mime');
		$validator->max('mime', 20);

		$validator->integer('size');
		$validator->required('size');

		$validator->date('created_at');
		$validator->date('updated_at');
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		$extension = $faker->fileExtension;
		return array(
			'name' => str_random(10) . ".$extension",
			'md5' => str_random(32),
			'sha1' => str_random(40),
			'original_name' => $faker->realText(100, 5) . ".$extension",
			'mime' => str_random(10),
			'size' => $faker->randomNumber(),
		);
	}

}
