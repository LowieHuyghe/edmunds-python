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

namespace Core\Bases\Analytics\Tracking\GA;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for Google-Analytics logs
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
	* //General
 * @property string $version
 * @property string $trackingId
 * @property string $anonymizeIp
 * @property string $dataSource
 * @property int $queueTime
 * @property string $cacheBuster
	//User
 * @property string $clientId
 * @property string $userId
	//Session
 * @property string $sessionControl
 * @property string $ipOverride
 * @property string $userAgentOverride
 * @property string $geographicalOverride
	//Traffic Sources
 * @property string $documentReferrer
 * @property string $campaignName
 * @property string $campaignSource
 * @property string $campaignMedium
 * @property string $campaignKeyword
 * @property string $campaignContent
 * @property string $campaignId
 * @property string $googleAdWordsId
 * @property string $googleDisplayAdsId
	//System Info
 * @property string $screenResolution
 * @property string $viewportSize
 * @property string $documentEncoding
 * @property string $screenColors
 * @property string $userLanguage
 * @property bool $javaEnabled
 * @property string $flashVersion
	//Hit
 * @property string $hitType
 * @property bool $nonInteractionHit
	//Content Information
 * @property string $documentLocationUrl
 * @property string $documentHostName
 * @property string $documentPath
 * @property string $documentTitle
 * @property string $screenName
 * @property string $linkId
	//App Tracking
 * @property string $applicationName
 * @property string $applicationId
 * @property string $applicationVersion
 * @property string $applicationInstallerId
	//Enhanced E-Commerce
 * @property array $productSku
 * @property array $productName
 * @property array $productBrand
 * @property array $productCategory
 * @property array $productVariant
 * @property array $productPrice
 * @property array $productQuantity
 * @property array $productCouponCode
 * @property array $productPosition
 * @property array $productCustomDimension
 * @property array $productCustomMetric
 * @property string $productAction
 * @property string $transactionId
 * @property string $affiliation
 * @property double $revenue
 * @property double $tax
 * @property double $shipping
 * @property string $couponCode
 * @property string $productActionList
 * @property int $checkoutStep
 * @property string $checkoutStepOption
 * @property array $productImpressionListName
 * @property array $productImpressionSku
 * @property array $productImpressionName
 * @property array $productImpressionBrand
 * @property array $productImpressionCategory
 * @property array $productImpressionVariant
 * @property array $productImpressionPosition
 * @property array $productImpressionPrice
 * @property array $productImpressionCustomDimension
 * @property array $productImpressionCustomMetric
 * @property array $promotionId
 * @property array $promotionName
 * @property array $promotionCreative
 * @property array $promotionPosition
 * @property string $promotionAction
	//Custom Dimensions/Metrics
 * @property array $customDimension
 * @property array $customMetric
	//ContentExperiments
 * @property string $experimentId
 * @property string $experimentVariant
 *
 */
class BaseLog extends \Core\Bases\Analytics\Tracking\BaseLog
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Set the version and tracking info
		$this->version = config('app.analytics.ga.version');
		$this->trackingId = config('app.analytics.ga.trackingid');
		$this->cacheBuster = rand(0, 2000000000);

		//Only if request is set
		if ($request = Request::getInstance())
		{
			$visitor = Visitor::getInstance();

			//Assign default values
			$this->dataSource = 'web';
			$this->userAgentOverride = $visitor->context->userAgent;
			$this->ipOverride = $request->ip;
			if ($visitor->loggedIn) $this->userId = $visitor->user->id;
			$this->userLanguage = $visitor->localization->locale;
			$this->clientId = $visitor->id;

			$this->documentEncoding = "UTF-8";
			$this->documentLocationUrl = $request->fullUrl;
			$this->documentHostName = $request->host;
			$this->documentPath = $request->path;
			if ($this->documentPath && $this->documentPath[0] != '/')
			{
				$this->documentPath = '/' . $this->documentPath;
			}
			$this->documentReferrer = $request->referrer;
		}

		$this->customDimension = array_merge($this->customDimension ?: array(), array(array('Environment', config('app.env', false))));
	}
}
