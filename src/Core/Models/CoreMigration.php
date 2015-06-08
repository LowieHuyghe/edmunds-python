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

/**
 * A class to use for migrations
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $migration The class that was migrated
 * @property string $version The version the table is on
 */
class CoreMigration extends BaseModel
{

	/**
	 * Make clear that this model uses static id's
	 */
	public $incrementing = false;

	/**
	 * Enable or disable timestamps by default
	 * @var boolean
	 */
	public $timestamps = true;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

}
