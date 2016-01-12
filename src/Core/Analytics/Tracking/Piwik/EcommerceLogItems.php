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

namespace Core\Analytics\Tracking\Piwik;

use Core\Bases\Analytics\Tracking\BaseLogValue;

/**
 * The structure for ecommerce logs
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
	// ecommerce items
 * @property string $sku
 * @property string $name
 * @property string $category
 * @property double $price
 * @property integer $quantity
 */
class EcommerceLogItems extends BaseLogValue
{
	/**
	 * The mapping of the parameters for the call
	 * @var array
	 */
	protected $parameterMapping = array(
		'sku' => 'sku',
		'name' => 'name',
		'category' => 'category',
		'price' => 'price',
		'quantity' => 'quantity',
	);

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		// $this->validator->value('sku');
		// $this->validator->value('name');
		// $this->validator->value('category');
		$this->validator->value('price')->numeric();
		$this->validator->value('quantity')->integer();
	}

}
