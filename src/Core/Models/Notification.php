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
 * The model for notifications
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $title
 * @property string $body
 * @property string $url
 * @property array $options
 * @property string $type
 * @property string $subtype
 */
class Notification extends BaseModel
{

	/**
	 * Add the validation of the model
	 * @param ValidationHelper $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->required('title');
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'title' => $faker->realText(100, 5),
			'body' => $faker->realText(100, 5),
			'url' => "/url",
			'options' => array(),
			'type' => str_random(10),
			'subtype' => str_random(10),
		);
	}
}
