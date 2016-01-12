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
	 * The mapping of the parameters for the call
	 * @var array
	 */
	protected $parameterMapping = array(
		//General
		'version' => 'v',
		'trackingId' => 'tid',
		'anonymizeIp' => 'aip',
		'dataSource' => 'ds',
		'queueTime' => 'qt',
		'cacheBuster' => 'z',
		//User
		'clientId' => 'cid',
		'userId'=> 'uid',
		//Session
		'sessionControl' => 'sc',
		'ipOverride' => 'uip',
		'userAgentOverride'=> 'ua',
		'geographicalOverride' => 'geoid',
		//Traffic Sources
		'documentReferrer' => 'dr',
		'campaignName' => 'cn',
		'campaignSource' => 'cs',
		'campaignMedium' => 'cm',
		'campaignKeyword' => 'ck',
		'campaignContent' => 'cc',
		'campaignId' => 'ci',
		'googleAdWordsId' => 'gclid',
		'googleDisplayAdsId' => 'dclid',
		//System Info
		'screenResolution' => 'sr',
		'viewportSize' => 'vp',
		'documentEncoding' => 'de',
		'screenColors' => 'sd',
		'userLanguage' => 'ul',
		'javaEnabled' => 'je',
		'flashVersion' => 'fl',
		//Hit
		'hitType' => 't',
		'nonInteractionHit' => 'ni',
		//Content Information
		'documentLocationUrl' => 'dl',
		'documentHostName' => 'dh',
		'documentPath' => 'dp',
		'documentTitle' => 'dt',
		'screenName' => 'cd',
		'linkId' => 'linkid',
		//App Tracking
		'applicationName' => 'an',
		'applicationId' => 'aid',
		'applicationVersion' => 'av',
		'applicationInstallerId' => 'aiid',
		//Event Tracking
		'eventCategory' => 'ec',
		'eventAction' => 'ea',
		'eventLabel' => 'el',
		'eventValue' => 'ev',
		//E-Commerce
		'transactionId' => 'ti',
		'transactionAffiliation' => 'ta',
		'transactionRevenue' => 'tr',
		'transactionShipping' => 'ts',
		'transactionTax' => 'tt',
		'itemName' => 'in',
		'itemPrice' => 'ip',
		'itemQuantity' => 'iq',
		'itemCode' => 'ic',
		'itemCategory' => 'iv',
		'currencyCode' => 'cu',
		//Enhanced E-Commerce
		'productSku' => 'pr{0}id',
		'productName' => 'pr{0}nm',
		'productBrand' => 'pr{0}br',
		'productCategory' => 'pr{0}ca',
		'productVariant' => 'pr{0}va',
		'productPrice' => 'pr{0}pr',
		'productQuantity' => 'pr{0}qt',
		'productCouponCode' => 'pr{0}cc',
		'productPosition' => 'pr{0}ps',
		'productCustomDimension' => 'pr{0}cd{1}',
		'productCustomMetric' => 'pr{0}cm{1}',
		'productAction' => 'pa',
		'affiliation' => 'ta',
		'revenue' => 'tr',
		'tax' => 'tt',
		'shipping' => 'ts',
		'couponCode' => 'tcc',
		'productActionList' => 'pal',
		'checkoutStep' => 'cos',
		'checkoutStepOption' => 'col',
		'productImpressionListName' => 'il{0}nm',
		'productImpressionSku' => 'il{0}pi{1}id',
		'productImpressionName' => 'il{0}pi{1}nm',
		'productImpressionBrand' => 'il{0}pi{1}br',
		'productImpressionCategory' => 'il{0}pi{1}ca',
		'productImpressionVariant' => 'il{0}pi{1}va',
		'productImpressionPosition' => 'il{0}pi{1}ps',
		'productImpressionPrice' => 'il{0}pi{1}pr',
		'productImpressionCustomDimension' => 'il{0}pi{1}cd{2}',
		'productImpressionCustomMetric' => 'il{0}pi{1}cm{2}',
		'promotionId' => 'promo{0}id',
		'promotionName' => 'promo{0}nm',
		'promotionCreative' => 'promo{0}cr',
		'promotionPosition' => 'promo{0}ps',
		'promotionAction' => 'promoa',
		//Social Interactions
		'socialNetwork' => 'sn',
		'socialAction' => 'sa',
		'socialActionTarget' => 'st',
		//Timing
		'userTimingCategory' => 'utc',
		'userTimingVariableName' => 'utv',
		'userTimingTime' => 'utt',
		'userTimingLabel' => 'utl',
		'pageLoadTime' => 'plt',
		'dnsTime' => 'dns',
		'pageDownloadTime' => 'pdt',
		'redirectResponseTime' => 'rrt',
		'tcpConnectTime' => 'tcp',
		'serverResponseTime' => 'srt',
		'domInteractiveTime' => 'dit',
		'contentLoadTime' => 'clt',
		//Exceptions
		'exceptionDescription' => 'exd',
		'exceptionFatal' => 'exf',
		//Custom Dimensions/Metrics
		'customDimension' => 'cd{0}',
		'customMetric' => 'cm{0}',
		//ContentExperiments
		'experimentId' => 'xid',
		'experimentVariant' => 'xvar',
	);

	/**
	 * The api-url
	 * @var string
	 */
	protected static $apiUrl = 'https://ssl.google-analytics.com/collect';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Set the version and tracking info
		$this->version = config('analytics.ga.version');
		$this->trackingId = config('analytics.ga.trackingid');
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

	/**
	 * Send the data
	 * @param string $apiUrl
	 * @param array $header
	 * @param array $data
	 * @param int $timeReported
	 */
	public static function send($apiUrl, $header, $data, $timeReported)
	{
		//Add queue time
		$queueTime = round((microtime(true) - $timeReported) * 1000);
		$data['qt'] = $queueTime;

		\Core\Bases\Analytics\Tracking\BaseLog::send($apiUrl, $header, $data, $timeReported);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		//General
		$this->validator->value('version')->required();
		$this->validator->value('trackingId')->required();
		$this->validator->value('anonymizeIp')->boolean();
		$this->validator->value('queueTime')->integer();
		//User
		$this->validator->value('clientId')->required();
		//Session
		$this->validator->value('ipOverride')->ip();
		//Traffic Sources
		$this->validator->value('documentReferrer')->url();
		//System Info
		$this->validator->value('javaEnabled')->boolean();
		//Hit
		$this->validator->value('hitType')->required();
		$this->validator->value('nonInteractionHit')->boolean();
		//Content Information
		$this->validator->value('documentLocationUrl')->url();
		$this->validator->value('screenName')->requiredIf('hitType', array('screenview'));
		//App Tracking
		$this->validator->value('applicationName')->requiredIf('dataSource', array('app'));
		//Event Tracking
		$this->validator->value('eventCategory')->requiredIf('hitType', array('event'));
		$this->validator->value('eventAction')->requiredIf('hitType', array('event'));
		$this->validator->value('eventValue')->integer();
		//E-Commerce
		$this->validator->value('transactionId')->requiredIf('hitType', array('transaction', 'item'));
		$this->validator->value('transactionRevenue')->numeric();
		$this->validator->value('transactionShipping')->numeric();
		$this->validator->value('transactionTax')->numeric();
		$this->validator->value('itemName')->requiredIf('hitType', array('item'));
		$this->validator->value('itemPrice')->numeric();
		$this->validator->value('itemQuantity')->integer();
		//Enhanced E-Commerce
		$this->validator->value('productSku')->sometimes(function($input) {
			$value = $input->productSku;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productName')->sometimes(function($input) {
			$value = $input->productName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productBrand')->sometimes(function($input) {
			$value = $input->productBrand;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productCategory')->sometimes(function($input) {
			$value = $input->productCategory;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productVariant')->sometimes(function($input) {
			$value = $input->productVariant;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productPrice')->sometimes(function($input) {
			$value = $input->productPrice;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_numeric($value[1]);
		});
		$this->validator->value('productQuantity')->sometimes(function($input) {
			$value = $input->productQuantity;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
		$this->validator->value('productCouponCode')->sometimes(function($input) {
			$value = $input->productCouponCode;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productPosition')->sometimes(function($input) {
			$value = $input->productPosition;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
		$this->validator->value('productCustomDimension')->sometimes(function($input) {
			$value = $input->productCustomDimension;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productCustomMetric')->sometimes(function($input) {
			$value = $input->productCustomMetric;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]);
		});
		$this->validator->value('revenue')->numeric();
		$this->validator->value('tax')->numeric();
		$this->validator->value('shipping')->numeric();
		$this->validator->value('checkoutStep')->integer();
		$this->validator->value('productImpressionListName')->sometimes(function($input) {
			$value = $input->productImpressionListName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('productImpressionSku')->sometimes(function($input) {
			$value = $input->productImpressionSku;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productImpressionName')->sometimes(function($input) {
			$value = $input->productImpressionName;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productImpressionBrand')->sometimes(function($input) {
			$value = $input->productImpressionBrand;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productImpressionCategory')->sometimes(function($input) {
			$value = $input->productImpressionCategory;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productImpressionVariant')->sometimes(function($input) {
			$value = $input->productImpressionVariant;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$this->validator->value('productImpressionPosition')->sometimes(function($input) {
			$value = $input->productImpressionPosition;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]);
		});
		$this->validator->value('productImpressionPrice')->sometimes(function($input) {
			$value = $input->productImpressionPrice;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_numeric($value[2]);
		});
		$this->validator->value('productImpressionCustomDimension')->sometimes(function($input) {
			$value = $input->productImpressionCustomDimension;
			return is_array($value) && count($value) == 4 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]) && 1 <= $value[2] && $value[2] <= 200;
		});
		$this->validator->value('productImpressionCustomMetric')->sometimes(function($input) {
			$value = $input->productImpressionCustomMetric;
			return is_array($value) && count($value) == 4 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]) && 1 <= $value[2] && $value[2] <= 200
				&& is_int_($value[3]);
		});
		$this->validator->value('promotionId')->sometimes(function($input) {
			$value = $input->promotionId;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('promotionName')->sometimes(function($input) {
			$value = $input->promotionName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('promotionCreative')->sometimes(function($input) {
			$value = $input->promotionCreative;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('promotionPosition')->sometimes(function($input) {
			$value = $input->promotionPosition;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		//Social Interactions
		$this->validator->value('socialNetwork')->requiredIf('hitType', array('social'));
		$this->validator->value('socialAction')->requiredIf('hitType', array('social'));
		$this->validator->value('socialActionTarget')->requiredIf('hitType', array('social'))->url();
		//Timing
		$this->validator->value('userTimingCategory')->requiredIf('hitType', array('timing'));
		$this->validator->value('userTimingVariableName')->requiredIf('hitType', array('timing'));
		$this->validator->value('userTimingTime')->requiredIf('hitType', array('timing'))->integer();
		$this->validator->value('pageLoadTime')->integer();
		$this->validator->value('dnsTime')->integer();
		$this->validator->value('pageDownloadTime')->integer();
		$this->validator->value('redirectResponseTime')->integer();
		$this->validator->value('tcpConnectTime')->integer();
		$this->validator->value('serverResponseTime')->integer();
		$this->validator->value('domInteractiveTime')->integer();
		$this->validator->value('contentLoadTime')->integer();
		//Exceptions
		$this->validator->value('exceptionFatal')->boolean();
		//Custom Dimensions/Metrics
		$this->validator->value('customDimension')->sometimes(function($input) {
			$value = $input->customDimension;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$this->validator->value('customMetric')->sometimes(function($input) {
			$value = $input->customMetric;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
	}
}
