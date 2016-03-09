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

namespace Core\Analytics\Tracking;

use Core\Analytics\Tracking\EcommerceItem;
use Core\Bases\Analytics\Tracking\BaseLog;
use Core\Localization\Format\DateTime;

/**
 * An ecommerce log
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
