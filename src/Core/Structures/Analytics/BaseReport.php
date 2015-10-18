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

namespace Core\Structures\Analytics;
use Core\Structures\Http\Request;
use Core\Structures\Http\Response;
use Core\Structures\Io\Validation;
use Core\Structures\Client\Visitor;
use Core\Structures\BaseStructure;

/**
 * The structure for reports
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
	//General
 * @property int $version
 * @property int $trackingId
 * @property int $anonymizeIp
 * @property int $dataSource
 * @property int $queueTime
 * @property int $cacheBuster
	//User
 * @property int $clientId
 * @property int $userId
	//Session
 * @property int $sessionControl
 * @property int $ip
 * @property int $userAgent
 * @property int $geoId
	//Traffic Sources
 * @property int $referrer
 * @property int $campaignName
 * @property int $campaignSource
 * @property int $campaignMedium
 * @property int $campaignKeyword
 * @property int $campaignContent
 * @property int $campaignId
 * @property int $googleAdWordsId
 * @property int $googleDisplayAdsId
	//System Info
 * @property int $screenResolution
 * @property int $viewportSize
 * @property int $documentEncoding
 * @property int $screenColors
 * @property int $userLanguage
 * @property int $javaEnabled
 * @property int $flahsVersion
	//Hit
 * @property int $type
 * @property int $nonInteractionHit
	//Content Information
 * @property int $documentLocationUrl
 * @property int $documentHostName
 * @property int $documentPath
 * @property int $documentTitle
 * @property int $screenName
 * @property int $linkId
	//App Tracking
 * @property int $applicationName
 * @property int $applicationId
 * @property int $applicationVersion
 * @property int $applicationInstallerId
	//Event Tracking
 * @property int $eventCategory
 * @property int $eventAction
 * @property int $eventLabel
 * @property int $eventValue
	//E-Commerce
 * @property int $transactionId
 * @property int $transactionAffiliation
 * @property int $transactionRevenue
 * @property int $transactionShipping
 * @property int $transactionTax
 * @property int $itemName
 * @property int $itemPrice
 * @property int $itemQuantity
 * @property int $itemCode
 * @property int $itemCategory
 * @property int $currencyCode
	//Enhanced E-Commerce
 * @property int $productSku
 * @property int $productName
 * @property int $productBrand
 * @property int $productCategory
 * @property int $productVariant
 * @property int $productPrice
 * @property int $productQuantity
 * @property int $productCouponCode
 * @property int $productPosition
 * @property int $productCustomDimension
 * @property int $productCustomMetric
 * @property int $productAction
 * @property int $affiliation
 * @property int $revenue
 * @property int $tax
 * @property int $shipping
 * @property int $couponCode
 * @property int $productActionList
 * @property int $checkoutStep
 * @property int $checkoutStepOption
 * @property int $productImpressionListName
 * @property int $productImpressionSku
 * @property int $productImpressionName
 * @property int $productImpressionBrand
 * @property int $productImpressionCategory
 * @property int $productImpressionVariant
 * @property int $productImpressionPosition
 * @property int $productImpressionPrice
 * @property int $productImpressionCustomDimension
 * @property int $productImpressionCustomMetric
 * @property int $promotionId
 * @property int $promotionName
 * @property int $promotionCreative
 * @property int $promotionPosition
 * @property int $promotionAction
	//Social Interactions
 * @property int $socialNetwork
 * @property int $socialAction
 * @property int $socialActionTarget
	//Timing
 * @property int $userTimingCategory
 * @property int $userTimingVariableName
 * @property int $userTimingTime
 * @property int $userTimingLabel
 * @property int $pageLoadTime
 * @property int $dnsTime
 * @property int $pageDownloadTime
 * @property int $redirectResponseTime
 * @property int $tcpConnectTime
 * @property int $serverResponseTime
 * @property int $domInteractiveTime
 * @property int $contentLoadTime
	//Exceptions
 * @property int $exceptionDescription
 * @property int $exceptionFatal
	//Custom Dimensions/Metrics
 * @property int $customDimension
 * @property int $customMetric
	//ContentExperiments
 * @property int $experimentId
 * @property int $experimentVariant
 *
 */
class BaseReport extends BaseStructure
{
	/**
	 * The mapping of the parameters for the call
	 * @var array
	 */
	private $parameterMapping = array(
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
		'ip' => 'uip',
		'userAgent'=> 'ua',
		'geoId' => 'geoid',
		//Traffic Sources
		'referrer' => 'dr',
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
		'type' => 't',
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
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Set the version and tracking info
		$this->version = env('ANALYTICS_GOOGLE_VERSION');
		$this->trackingId = env('ANALYTICS_GOOGLE_TRACKINGID');

		$request = Request::current();
		$response = Response::current();
		$visitor = Visitor::current();

		//Assign default values
		$this->userAgent = $visitor->environment->userAgent;
		$this->ip = $request->ip;
		if ($visitor->isLoggedIn()) $this->userId = $visitor->user->id;
		$this->userLanguage = $visitor->localization->locale;
		$this->clientId = $visitor->id;

		$this->validator = new Validation();
		static::addValidationRules($this->validator);
	}

	/**
	 * Save the instance of the report
	 * @return mixed response
	 */
	public function report()
	{
		if (get_class() == get_called_class())
		{
			throw new \Exception('The BaseReport can not be reported for analytics');
			return null;
		}
		if ($this->hasErrors())
		{
			throw new \Exception('This report has errors and can not be sent: ' . json_encode($this->getErrors()->getMessageBag()->toArray()));
			return null;
		}

		$output = array();
		//Add cache buster to make sure link is loaded
		$this->attributes['cacheBuster'] = rand(0, 2000000000);

		//Set up all the variables and the right values
		foreach ($this->attributes as $parameter => $value)
		{
			if (!isset($this->parameterMapping[$parameter]))
			{
				throw new \Exception("There is no mapping for the parameter: $parameter");
				return null;
			}

			//Bool needs to be 1/0
			if (is_bool($value))
			{
				$value = $value ? 1 : 0;
			}

			//Some parameter-names need to be filled in
			$parameterName = $this->parameterMapping[$parameter];
			if (is_array($value))
			{
				for ($i=0 ; $i < count($value)-1 ; ++$i)
				{
					$parameterName = str_replace("{$i}", $value[$i], $parameterName);
				}
				$value = last($value);
			}

			//Add query-item
			$output[$parameterName] = urlencode($value);
		}

		return $this->send($output);
	}

	private function send($data)
	{
		//Setup header
		$header = array('Content-type: application/x-www-form-urlencoded');
		if ($this->userAgent)
		{
			$header[] = 'User-Agent: ' . $this->userAgent;
		}

		//Setup options
		$options = array(
			'http' => array(
				'header'  => $header,
				'method'  => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context  = stream_context_create($options);

		//Send request
		$result = file_get_contents('http://www.google-analytics.com/collect', false, $context);

		//Return result
		return $result;
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		//General
		$validator->value('version')->required();
		$validator->value('trackingId')->required();
		$validator->value('anonymizeIp')->boolean();
		$validator->value('queueTime')->integer();
		//User
		$validator->value('clientId')->required();
		//Session
		$validator->value('ip')->ip();
		//System Info
		$validator->value('javaEnabled')->boolean();
		//Hit
		$validator->value('type')->required();
		$validator->value('nonInteractionHit')->boolean();
		//Content Information
		$validator->value('screenName')->requiredIf('type', array('screenview'))->sometimes(function($input) {
			return (isset($input->dataSource) && in_array($input->dataSource, array('app')));
		});
		//App Tracking
		$validator->value('applicationName')->requiredIf('dataSource', array('app'));
		//Event Tracking
		$validator->value('eventCategory')->requiredIf('type', array('event'));
		$validator->value('eventAction')->requiredIf('type', array('event'));
		$validator->value('eventValue')->integer();
		//E-Commerce
		$validator->value('transactionId')->requiredIf('type', array('transaction', 'item'));
		$validator->value('transactionRevenue')->numeric();
		$validator->value('transactionShipping')->numeric();
		$validator->value('transactionTax')->numeric();
		$validator->value('itemName')->requiredIf('type', array('item'));
		$validator->value('itemPrice')->numeric();
		$validator->value('itemQuantity')->integer();
		//Enhanced E-Commerce
		$validator->value('productPrice')->numeric();
		$validator->value('productQuantity')->integer();
		$validator->value('productPosition')->integer();
		$validator->value('productCustomMetric')->integer();
		$validator->value('revenue')->numeric();
		$validator->value('tax')->numeric();
		$validator->value('shipping')->numeric();
		$validator->value('checkoutStep')->integer();
		$validator->value('productImpressionPosition')->integer();
		$validator->value('productImpressionPrice')->numeric();
		$validator->value('productImpressionCustomMetric')->integer();
		//Social Interactions
		$validator->value('socialNetwork')->requiredIf('type', array('social'));
		$validator->value('socialAction')->requiredIf('type', array('social'));
		$validator->value('socialActionTarget')->requiredIf('type', array('social'));
		//Timing
		$validator->value('userTimingCategory')->requiredIf('type', array('timing'));
		$validator->value('userTimingVariableName')->requiredIf('type', array('timing'));
		$validator->value('userTimingTime')->requiredIf('type', array('timing'))->integer();
		$validator->value('pageLoadTime')->integer();
		$validator->value('dnsTime')->integer();
		$validator->value('pageDownloadTime')->integer();
		$validator->value('redirectResponseTime')->integer();
		$validator->value('tcpConnectTime')->integer();
		$validator->value('serverResponseTime')->integer();
		$validator->value('domInteractiveTime')->integer();
		$validator->value('contentLoadTime')->integer();
		//Exceptions
		$validator->value('exceptionFatal')->boolean();
		//Custom Dimensions/Metrics
		$validator->value('customMetric')->integer();
	}
}
