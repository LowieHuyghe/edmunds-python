<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Foundation\Helpers;

use Core\Bases\Helpers\BaseHelper;

/**
 * The helper responsible for image processing
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class ImageHelper extends BaseHelper
{
	/**
	 * Crop an image
	 * @param resource $resource
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @return bool
	 */
	public static function crop(&$resource, $width, $height, $x = 0, $y = 0)
	{
		$result = imagecrop($resource, array('width' => $width, 'height' => $height, 'x' => $x, 'y' => $y));
		if ($result !== false)
		{
			$resource = $result;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Resize an image
	 * @param resource $resource
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	public static function resize(&$resource, $width, $height = -1)
	{
		$result = imagescale($resource, $width, $height);
		if ($result !== false)
		{
			$resource = $result;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Flip the image horizontal
	 * @param resource $resource
	 * @return bool
	 */
	public static function flipHorizontal(&$resource)
	{
		return imageflip($resource, IMG_FLIP_HORIZONTAL);
	}

	/**
	 * Flip the image vertical
	 * @param resource $resource
	 * @return bool
	 */
	public static function flipVertical(&$resource)
	{
		return imageflip($resource, IMG_FLIP_VERTICAL);
	}

	/**
	 * Flip the image horizontal and vertical
	 * @param resource $resource
	 * @return bool
	 */
	public static function flipBoth(&$resource)
	{
		return imageflip($resource, IMG_FLIP_BOTH);
	}

	/**
	 * Rotate the image
	 * @param resource $resource
	 * @param float $degrees
	 * @param int $bgcolor
	 * @return bool
	 */
	public static function rotate(&$resource, $degrees, $bgcolor = 0)
	{
		$result = imagerotate($resource, $degrees, $bgcolor);
		if ($result !== false)
		{
			$resource = $result;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Apply a filter
	 * @param resource $resource
	 * @param int $filter
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @param mixed $arg3
	 * @param mixed $arg4
	 * @return bool
	 */
	private static function filter(&$resource, $filter, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
	{
		$count = (is_null($arg1) ? 0 : 1) + (is_null($arg2) ? 0 : 1) + (is_null($arg3) ? 0 : 1) + (is_null($arg4) ? 0 : 1);

		switch ($count)
		{
			case 0:
				return imagefilter($resource, $filter);
			case 1:
				return imagefilter($resource, $filter, $arg1);
			case 2:
				return imagefilter($resource, $filter, $arg1, $arg2);
			case 3:
				return imagefilter($resource, $filter, $arg1, $arg2, $arg3);
			case 4:
			default:
				return imagefilter($resource, $filter, $arg1, $arg2, $arg3, $arg4);
		}
	}

	/**
	 * Apply grayscale filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterGrayscale(&$resource)
	{
		return self::filter($resource, IMG_FILTER_GRAYSCALE);
	}

	/**
	 * Apply negative filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterNegative(&$resource)
	{
		return self::filter($resource, IMG_FILTER_NEGATE);
	}

	/**
	 * Apply brightness filter
	 * @param resource $resource
	 * @param double $brightness -1 to 1
	 * @return bool
	 */
	public static function filterBrightness(&$resource, $brightness)
	{
		return self::filter($resource, IMG_FILTER_BRIGHTNESS, $brightness * 255);
	}

	/**
	 * Apply contrast filter
	 * @param resource $resource
	 * @param double $contrast -1 to 1
	 * @return bool
	 */
	public static function filterContrast(&$resource, $contrast)
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
	 * @return bool
	 */
	public static function filterColorize(&$resource, $r, $g, $b, $a = 1.0)
	{
		return self::filter($resource, IMG_FILTER_CONTRAST, $r, $g, $b, $a * 127);
	}

	/**
	 * Apply edge detect filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterEdgedetect(&$resource)
	{
		return self::filter($resource, IMG_FILTER_EDGEDETECT);
	}

	/**
	 * Apply emboss filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterEmboss(&$resource)
	{
		return self::filter($resource, IMG_FILTER_EMBOSS);
	}

	/**
	 * Apply gaussian blur filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterGaussianBlur(&$resource)
	{
		return self::filter($resource, IMG_FILTER_GAUSSIAN_BLUR);
	}

	/**
	 * Apply selective blur filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterSelectiveBlur(&$resource)
	{
		return self::filter($resource, IMG_FILTER_SELECTIVE_BLUR);
	}

	/**
	 * Apply mean removal filter
	 * @param resource $resource
	 * @return bool
	 */
	public static function filterSketchy(&$resource)
	{
		return self::filter($resource, IMG_FILTER_MEAN_REMOVAL);
	}

	/**
	 * Apply smooth filter
	 * @param resource $resource
	 * @param int $smooth -1 to 1
	 * @return bool
	 */
	public static function filterSmooth(&$resource, $smooth)
	{
		return self::filter($resource, IMG_FILTER_SMOOTH, $smooth * 8);
	}

	/**
	 * Apply pixelate filter
	 * @param resource $resource
	 * @param int $blockSize
	 * @param bool $advanced
	 * @return bool
	 */
	public static function filterPixelate(&$resource, $blockSize, $advanced = false)
	{
		return self::filter($resource, IMG_FILTER_PIXELATE, $blockSize, $advanced);
	}

}
