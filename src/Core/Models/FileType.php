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

/**
 * The model for file-type
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class FileType extends BaseEnumModel
{
	const	AUDIO			= 1,
			IMAGE			= 2,
			DOCUMENT		= 3,
			VIDEO			= 4,
			ARCHIVE			= 5,
			EXECUTABLE		= 6;

	/**
	 * Mapping of the types and mimes
	 * @var array
	 */
	protected static $mapping = array(
		self::AUDIO => array(
			'audio/mpeg3'						=> array('mp3'),
			'audio/x-mpeg-3'					=> array('mp3'),
			'audio/x-mid'						=> array('midi'),
			'audio/x-midi'						=> array('midi'),
			'audio/midi'						=> array('midi'),
		),
		self::IMAGE => array(
			'image/gif'							=> array('gif'),
			'image/jpeg'						=> array('jpg', 'jpeg'),
			'image/pjpeg'						=> array('jpg', 'jpeg'),
			'image/png'							=> array('png'),
		),
		self::DOCUMENT => array(
			'application/pdf'					=> array('pdf'),
		),
		self::ARCHIVE => array(
			'application/zip'					=> array('zip'),
			'application/x-rar-compressed'		=> array('rar'),
		),
		self::EXECUTABLE => array(
			'application/octet-stream'			=> array('exe'),
			'application/java-archive'			=> array('jar'),
			'application/x-apple-diskimage'		=> array('dmg'),
		),
		self::VIDEO => array(
			'video/mpeg'						=> array('mp4'),
			'video/x-mpeg'						=> array('mp4'),
			'video/avi'							=> array('avi'),
			'video/msvideo'						=> array('avi', 'wmv'),
			'video/x-msvideo'					=> array('avi', 'wmv'),
		),
	);

	/**
	 * Return the type of the file from the mime
	 * @param string $mime
	 * @param string $extension
	 * @return int
	 */
	public static function getType($mime, $extension)
	{
		$mime = strtolower($mime);
		$extension = strtolower($extension);

		foreach (self::$mapping as $type => $mimes)
		{
			foreach ($mimes as $mimesMime => $mimesExtensions)
			{
				if ($mimesMime == $mime && in_array($extension, $mimesExtensions))
				{
					return self::find($type);
				}
			}
		}
		return null;
	}

	/**
	 * Get all the extensions possible for a given type
	 * @param int $typeId
	 * @return array
	 */
	public static function getExtensionsForType($typeId)
	{
		$extensions = array();

		foreach (self::$mapping[$typeId] as $mimesMime => $mimesExtensions)
		{
			$extensions = array_merge($extensions, $mimesExtensions);
		}

		return sort(array_unique($extensions));
	}
}
