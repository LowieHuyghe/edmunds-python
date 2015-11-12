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

namespace Core\Http;

use Core\Bases\Structures\BaseStructure;

/**
 * The structure for the routes
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $name Ex: getName
 * @property int $namePosition Ex: 1 when /12/{name}/ab
 * @property array $parameters Ex: array('\d+', \w+)
 * @property array $rights Ex: array(1, 2, 3)
 * @property array $middleware Ex: array('auth',)
 */
class Route extends BaseStructure
{

	/**
	 * Constructor
	 * @param string $name Ex: getName
	 * @param int $namePosition Ex: 1 when /12/{name}/ab
	 * @param array $parameters Ex: array('\d+', \w+)
	 * @param array $rights Ex: array(1, 2, 3)
	 * @param array $middleware Ex: array('auth')
	 */
	public function __construct($name, $parameters = array(), $namePosition = 0, $rights = array(), $middleware = array())
	{
		parent::__construct();

		$this->name = $name;
		$this->parameters = $parameters;
		$this->namePosition = $namePosition;
		$this->rights = $rights;
		$this->middleware = $middleware;
	}

}