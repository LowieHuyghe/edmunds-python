<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Analytics\Tracking;

use Edmunds\Analytics\Tracking\EcommerceItem;
use Edmunds\Bases\Analytics\Tracking\BaseLog;
use Edmunds\Localization\Format\DateTime;

/**
 * An ecommerce log
 *
 * @property string $id
 * @property string $category
 * @property float $subtotal Subtotal excluding shipping
 * @property float $shipping
 * @property float $tax
 * @property float $discount
 * @property float $revenue The grand total
 * @property string $currencyCode
 * @property EcommerceItem[] $items
 * @property DateTime $previous Previous buy
 */
class EcommerceLog extends BaseLog
{
	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$validator->rule('id')->required();
		$validator->rule('revenue')->numeric()->required();
		$validator->rule('subtotal')->numeric();
		$validator->rule('shipping')->numeric();
		$validator->rule('tax')->numeric();
		$validator->rule('discount')->numeric();
		$validator->rule('items')->array_();
		$validator->rule('previous')->date();
	}
}
