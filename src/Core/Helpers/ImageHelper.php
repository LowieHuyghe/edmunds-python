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

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The helper responsible for image processing
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ImageHelper extends BaseHelper
{

	/**
	 * Get the resource from a fileEntryId or input
	 * @param UploadedFile|int $file
	 * @return resource|null
	 */
	public static function getResource($file)
	{
		if (is_a($file, UploadedFile::class))
		{

		}
		elseif (is_numeric($file))
		{

		}
	}

	/**
	 * Crop an image
	 * @param resource $resource
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @return null|resource.
	 */
	public static function crop($resource, $width, $height, $x = 0, $y = 0)
	{
		$result = imagecrop($resource, array('width' => $width, 'height' => $height, 'x' => $x, 'y' => $y));
		return ($result !== false) ? $result : null;
	}

	/**
	 * Resize an image
	 * @param resource $resource
	 * @param int $width
	 * @param int $heigth
	 * @return null|resource
	 */
	public static function resize($resource, $width, $heigth = -1)
	{
		$result = imagescale($resource, $width, $heigth);
		return ($result !== false) ? $result : null;
	}

	/**
	 * Flip the image horizontal
	 * @param $resource
	 * @return bool|null
	 */
	public static function flipHorizontal($resource)
	{
		$result = imageflip($resource, IMG_FLIP_HORIZONTAL);
		return ($result !== false) ? $result : null;
	}

	/**
	 * Flip the image vertical
	 * @param $resource
	 * @return bool|null
	 */
	public static function flipVertical($resource)
	{
		$result = imageflip($resource, IMG_FLIP_VERTICAL);
		return ($result !== false) ? $result : null;
	}

	/**
	 * Flip the image horizontal and vertical
	 * @param $resource
	 * @return bool|null
	 */
	public static function flipBoth($resource)
	{
		$result = imageflip($resource, IMG_FLIP_BOTH);
		return ($result !== false) ? $result : null;
	}

	/**
	 * Apply a filter
	 * @param $resource
	 * @param $filter
	 * @param array $arguments
	 * @return resource|null
	 */
	private static function filter($resource, $filter, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
	{
		$result = imagefilter($resource, $filter, $arg1, $arg2, $arg3, $arg4);
		return ($result !== false) ? $result : null;
	}

	/**
	 * Apply grayscale filter
	 * @param $resource
	 * @return null|resource
	 */
	public static function filterGrayscale($resource)
	{
		return self::filter($resource, IMG_FILTER_GRAYSCALE);
	}

	/**
	 * Apply negative filter
	 * @param $resource
	 * @return null|resource
	 */
	public static function filterNegative($resource)
	{
		return self::filter($resource, IMG_FILTER_NEGATE);
	}

	/**
	 * Apply brightness filter
	 * @param resource $resource
	 * @param double $brightness -1 to 1
	 * @return null|resource
	 */
	public static function filterBrightness($resource, $brightness)
	{
		return self::filter($resource, IMG_FILTER_BRIGHTNESS, $brightness * 255);
	}

	/**
	 * Apply contrast filter
	 * @param resource $resource
	 * @param double $contrast -1 to 1
	 * @return null|resource
	 */
	public static function filterContrast($resource, $contrast)
	{
		return self::filter($resource, IMG_FILTER_CONTRAST, $contrast * 100);
	}

	/**
	 * Apply colorize filter
	 * @param resource $resource
	 * @param int $r 0 to 255
	 * @param int $g 0 to 255
	 * @param int $b 0 to 255
	 * @param double $a 0 to 1
	 * @return null|resource
	 */
	public static function filterColorize($resource, $r, $g, $b, $a = 1)
	{
		return self::filter($resource, IMG_FILTER_CONTRAST, $r, $g, $b, $a * 100);
	}

	/**
	 * Apply edge detect filter
	 * @param resource $resource
	 * @return null|resource
	 */
	public static function filterEdgedetect($resource)
	{
		return self::filter($resource, IMG_FILTER_EDGEDETECT);
	}

	/**
	 * Apply emboss filter
	 * @param resource $resource
	 * @return null|resource
	 */
	public static function filterEmboss($resource)
	{
		return self::filter($resource, IMG_FILTER_EMBOSS);
	}

	/**
	 * Apply gaussian blur filter
	 * @param resource $resource
	 * @return null|resource
	 */
	public static function filterGaussianBlur($resource)
	{
		return self::filter($resource, IMG_FILTER_GAUSSIAN_BLUR);
	}

	/**
	 * Apply selective blur filter
	 * @param resource $resource
	 * @return null|resource
	 */
	public static function filterSelectiveBlur($resource)
	{
		return self::filter($resource, IMG_FILTER_SELECTIVE_BLUR);
	}

	/**
	 * Apply mean removal filter
	 * @param resource $resource
	 * @return null|resource
	 */
	public static function filterSketchy($resource)
	{
		return self::filter($resource, IMG_FILTER_MEAN_REMOVAL);
	}

	/**
	 * Apply smooth filter
	 * @param resource $resource
	 * @param int $smooth -1 to 1
	 * @return null|resource
	 */
	public static function filterSmooth($resource, $smooth)
	{
		return self::filter($resource, IMG_FILTER_SMOOTH, $smooth * 8);
	}

	/**
	 * Apply pixelate filter
	 * @param resource $resource
	 * @param int $blockSize
	 * @param bool $advanced
	 * @return null|resource
	 */
	public static function filterPixelate($resource, $blockSize, $advanced = false)
	{
		return self::filter($resource, IMG_FILTER_PIXELATE, $blockSize, $advanced);
	}

}
