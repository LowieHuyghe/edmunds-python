<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Analytics\Tracking;

use Edmunds\Bases\Structures\BaseStructure;

/**
 * An ecommerce item
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 *
 * @property string $id
 * @property string $name
 * @property string $category
 * @property float $price
 * @property int $quantity
 */
class EcommerceItem extends BaseStructure
{
	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$validator->rule('id')->required();
		$validator->rule('category')->required();
		$validator->rule('name')->required();
		$validator->rule('price')->numeric()->required();
		$validator->rule('quantity')->integer()->required();
	}
}
