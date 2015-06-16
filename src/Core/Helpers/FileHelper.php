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

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use LH\Core\Models\FileEntry;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\JsonResponse;
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

		//Make fileEntry
		$fileEntry = new FileEntry();
		$fileEntry->name = $name;
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
	 * Delete file from storage
	 * @param int $fileEntryId
	 * @return bool Success
	 */
	public static function delete($fileEntryId)
	{
		$fileEntry = FileEntry::findOrFail($fileEntryId);

		if (self::exists($fileEntry->name))
		{
			if (self::getDisk()->delete($fileEntry->name))
			{
				if ($fileEntry->delete())
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if file exists
	 * @param $name
	 * @return bool
	 */
	private static function exists($name)
	{
		return self::getDisk()->exists($name);
	}

	/**
	 * Get the disk to handle files
	 * @return mixed
	 */
	private static function getDisk()
	{
		return Storage::disk(Config::get('filesystems.default'));
	}
}
