<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Analytics\Tracking;

use Edmunds\Bases\Structures\BaseStructure;

/**
 * An ecommerce item
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
