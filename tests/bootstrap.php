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

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('LH\\Core\\', __DIR__.'/../src/Core');
$loader->addPsr4('LH\\CoreTest\\', __DIR__.'/Core');

$result = array_sort(array('1.0', '1.0.1', '2', '0.1.2.3.4', '3'), function($el1, $el2) {
	$el1s = explode('.', $el1);
	$el2s = explode('.', $el2);

	for ($i = 0; $i < max(array(count($el1s), count($el2s))); ++$i)
	{
		if (!isset($el1s[$i]) && isset($el2s[$i]))
		{
			return true;
		}
		elseif (isset($el1s[$i]) && !isset($el2s[$i]))
		{
			return false;
		}
		elseif ($el1s[$i] != $el2s[$i])
		{
			return $el1s[$i] > $el2s[$i];
		}
	}

	return true;
});

ksort($result);

$result = array_values($result);

var_dump($result);