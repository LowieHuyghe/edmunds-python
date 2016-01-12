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

use Core\Bases\Analytics\Tracking\Piwik\BaseLog;

/**
 * The structure for content logs
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
	// optional ecommerce info
 * @property string $ecommerceId
 * @property string $ecommerceItems
 * @property double $ecommerceRevenue
 * @property double $ecommerceSubtotal
 * @property double $ecommerceTax
 * @property double $ecommerceShippingCost
 * @property double $ecommerceDiscount
 * @property integer $ecommercePreviousTime
 */
class EcommerceLog extends BaseLog
{
	//
}
