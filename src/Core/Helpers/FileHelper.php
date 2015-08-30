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

namespace LH\Core\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LH\Core\Models\FileEntry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for the files
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class FileHelper extends BaseHelper
{
	/**
	 * Upload a file to the given location
	 * @param UploadedFile $file
	 * @return FileEntry
	 */
	public static function store($file)
	{
		//Fetch data
		$extension = $file->getClientOriginalExtension();
		do {
			$name = str_random(7) . '.' . $extension;
		} while (self::exists($name));
		$originalName = $file->getClientOriginalName();
		$mime = $file->getMimeType();
		$size = $file->getSize();
		$md5 = md5_file($file->getPathname());
		$sha1 = sha1_file($file->getPathname());

		//Make fileEntry
		$fileEntry = new FileEntry();
		$fileEntry->name = $name;
		$fileEntry->md5 = $md5;
		$fileEntry->sha1 = $sha1;
		$fileEntry->original_name = $originalName;
		$fileEntry->mime = $mime;
		$fileEntry->size = $size;

		//Check if there are errors
		if (!$fileEntry->hasErrors())
		{
			//Upload the file
			if (self::getDisk()->put($name, File::get($file)))
			{
				$fileEntry->save();
				return $fileEntry;
			}
		}

		return null;
	}

	/**
	 * Return the fileEntry from the id
	 * @param int $fileEntryId
	 * @return FileEntry
	 */
	public static function get($fileEntryId)
	{
		$fileEntry = FileEntry::find($fileEntryId);

		return $fileEntry;
	}

	/**
	 * Delete file from storage
	 * @param int $fileEntryId
	 * @return bool Success
	 */
	public static function delete($fileEntryId)
	{
		$fileEntry = FileEntry::find($fileEntryId);
		//Check if row exists
		if (!$fileEntry)
		{
			return false;
		}

		//Delete file if exists
		$deleted = true;
		if (self::exists($fileEntry->name))
		{
			if (!self::getDisk()->delete($fileEntry->name))
			{
				$deleted = false;
			}
		}

		//Delete record when file doesn't exist anymore
		if ($deleted && $fileEntry->delete())
		{
			return true;
		}

		return false;
	}
}
