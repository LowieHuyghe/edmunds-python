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