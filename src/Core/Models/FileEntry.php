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

namespace Core\Models;
use Faker\Generator;
use Core\Database\Relations\HasOneEnum;
use Core\Structures\Client\Input;
use Core\Structures\Io\Validation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The model for files
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property string $name
 * @property string $md5
 * @property string $sha1
 * @property string $original_name
 * @property string $mime
 * @property FileType $type
 * @property int $size
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
	 * The path of the uploaded file
	 * @var UploadedFile
	 */
	private $uploadedFile;

	/**
	 * The name of the last changed resource of the file (to save)
	 * @var string
	 */
	private $lastTouched;

	/**
	 * The class responsible for the type
	 * @var string
	 */
	protected $typeClass = FileType::class;

	/**
	 * Type belonging to this FileEntry
	 * @return HasOneEnum
	 */
	public function type()
	{
		if (!isset($this->typeClass))
		{
			throw new Exception('The class representing the Types not set');
		}
		return $this->hasOneEnum($this->typeClass);
	}

	/**
	 * Save the file-entry and upload the file
	 * @param array $options
	 * @return bool
	 */
	public function save(array $options = Array())
	{
		//Set the name for the file when saving
		if (!$this->name)
		{
			$this->name = self::getNewName($this->getExtension());
		}

		//First save the file to the storage
		switch($this->lastTouched)
		{
			case 'resource':
				ob_start();
				switch($this->mime)
				{
					case 'image/png':
						imagepng($this->resource);
						break;
					case 'image/pjpeg':
					case 'image/jpeg':
						imagejpeg($this->resource);
						break;
					case 'image/gif':
						imagegif($this->resource);
						break;
					default:
						ob_end_clean();
						return false;
				}
				$contents =  ob_get_contents();
				ob_end_clean();
				$uploadSuccess = self::getDisk()->put($this->name, $contents);
				break;
			case 'uploadedFile':
				$uploadSuccess = self::getDisk()->put($this->name, app('file')->get($this->uploadedFile));
				break;
			default:
				$uploadSuccess = true;
				break;
		}

		//If success save the entry in the database
		if ($uploadSuccess)
		{
			return parent::save($options);
		}

		return false;
	}

	/**
	 * Save the fil-entry as a new entry
	 * @param array $options
	 * @return bool
	 */
	public function saveAs(array $options = Array())
	{
		$this->id = null;
		$this->name = null;

		return $this->save($options);
	}

	/**
	 * Delete the file completely
	 * @return bool
	 */
	public function delete()
	{
		//Delete the file on the storage
		$deleted = true;
		if ($this->name && $this->getFileExist())
		{
			if (!self::getDisk()->delete($this->name))
			{
				$deleted = false;
			}
		}

		//Delete record when file doesn't exist anymore
		if ($deleted && parent::delete())
		{
			return true;
		}

		return false;
	}

	/**
	 * Get the complete path to the file
	 * @return string
	 */
	public function getPath()
	{
		//Get the full path of the uploaded temporary file
		if ($this->lastTouched == 'uploadedFile')
		{
			return $this->uploadedFile->getRealPath();
		}

		//Get the full path of the file on the storage server
		if ($this->name)
		{
			return self::getDisk()->getDriver()->getAdapter()->getPathPrefix() . $this->name;
		}

		return null;
	}

	/**
	 * Check if saved file exists on server
	 * @return bool
	 */
	public function getFileExist()
	{
		//Check if file on server
		if ($this->name)
		{
			return self::getDisk()->exists($this->name);
		}

		return false;
	}

	/**
	 * Get the extension of the file
	 * @return string
	 */
	public function getExtension()
	{
		return pathinfo($this->original_name, PATHINFO_EXTENSION);
	}

	/**
	 * Get the resource based on the file (only for images)
	 * @return resource
	 */
	public function getResource()
	{
		//Check if resource doesn't already exist
		if (isset($this->resource))
		{
			return $this->resource;
		}

		//Fill in the resource
		switch($this->mime)
		{
			case 'image/png':
				$this->resource = imagecreatefrompng($this->getPath());
				break;
			case 'image/pjpeg':
			case 'image/jpeg':
				$this->resource = imagecreatefromjpeg($this->getPath());
				break;
			case 'image/gif':
				$this->resource = imagecreatefromgif($this->getPath());
				break;
			default:
				return null;
		}

		//Return the resource
		return $this->resource;
	}

	/**
	 * Set the changed resource
	 * @param resource $resource
	 */
	public function setResource($resource)
	{
		if ($resource)
		{
			$this->resource = $resource;
			$this->lastTouched = 'resource';
		}
	}

	/**
	 * Get the disk to handle files
	 * @return mixed
	 */
	private static function getDisk()
	{
		return app('storage')->disk(config('filesystems.default'));
	}

	/**
	 * Get a new name that doesn't exist
	 * @param string $extension
	 * @return string
	 */
	private static function getNewName($extension)
	{
		do {
			$name = str_random(7) . '.' . $extension;
		} while (self::getDisk()->exists($name));

		return $name;
	}

	/**
	 * Generate a fileEntry from an uploaded file
	 * @param string $name
	 * @return FileEntry
	 */
	public static function generateFromInput($name)
	{
		//Fetch the uploaded file
		$uploadedFile = Input::current()->file($name);
		if (!$uploadedFile)
		{
			return null;
		}

		//Fetch data
		$originalName = $uploadedFile->getClientOriginalName();
		$mime = $uploadedFile->getMimeType();
		$size = $uploadedFile->getSize();
		$md5 = md5_file($uploadedFile->getPathname());
		$sha1 = sha1_file($uploadedFile->getPathname());
		$type = FileType::getType($mime, $uploadedFile->getClientOriginalExtension());

		//Make fileEntry
		$fileEntry = new FileEntry();
		$fileEntry->md5 = $md5;
		$fileEntry->sha1 = $sha1;
		$fileEntry->original_name = $originalName;
		$fileEntry->mime = $mime;
		$fileEntry->type = $type;
		$fileEntry->size = $size;

		//Set file
		$fileEntry->uploadedFile = $uploadedFile;
		$fileEntry->lastTouched = 'uploadedFile';

		return $fileEntry;
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->value('id')->integer();
		$validator->value('name')->required()->max(20)->unique('file_entries');
		$validator->value('md5')->required()->max(32);
		$validator->value('sha1')->required()->max(40);
		$validator->value('original_name')->required()->max(255);
		$validator->value('mime')->required()->max(20);
		$validator->value('type')->required();
		$validator->value('size')->required()->integer();

		$validator->value('created_at')->date();
		$validator->value('updated_at')->date();
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
			'type' => $faker->randomNumber(),
			'size' => $faker->randomNumber(),
		);
	}

}
