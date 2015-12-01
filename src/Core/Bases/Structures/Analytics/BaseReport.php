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

namespace Core\Bases\Structures\Analytics;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for reports
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
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Set the version and tracking info
		$this->version = config('analytics.google.version');
		$this->trackingId = config('analytics.google.trackingid');
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
	 * Report the log
	 * @throws \Exception
	 */
	public function report()
	{
//		if ($this->hasErrors())
//		{
//			throw new \Exception('This report has errors and can not be sent: ' . json_encode($this->getErrors()->getMessageBag()->toArray()));
//		}

		//Set up all the variables and the right values
		$data = array();
		foreach ($this->attributes as $parameter => $value)
		{
			if (!isset($this->parameterMapping[$parameter]))
			{
				//throw new \Exception("There is no mapping for the parameter: $parameter");
				continue;
			}

			//Bool needs to be 1/0
			if (is_bool($value))
			{
				$value = $value ? 1 : 0;
			}

			if (is_array($value))
			{
				foreach ($value as $customValue)
				{
					//Some parameter-names need to be filled in
					$parameterName = $this->parameterMapping[$parameter];

					for ($i=0 ; $i < count($customValue)-1 ; ++$i)
					{
						$parameterName = str_replace('{' . $i . '}', $customValue[$i], $parameterName);
					}
					$customValue = last($customValue);

					//Add query-item
					$data[$parameterName] = $customValue;
				}
			}
			else
			{
				//Some parameter-names need to be filled in
				$parameterName = $this->parameterMapping[$parameter];

				//Add query-item
				$data[$parameterName] = $value;
			}
		}

		//Setup header
		$header = array('Content-type: application/x-www-form-urlencoded');
		if ($this->userAgentOverride)
		{
			$header[] = 'User-Agent: ' . $this->userAgentOverride;
		}

		Registry::queue()->dispatch(array(get_called_class(), 'send'), array(
			$header, $data, microtime(true),
		), Queue::QUEUE_LOG);
	}

	/**
	 * Send the data
	 * @param array $header
	 * @param array $data
	 * @param int $timeReported
	 */
	public static function send($header, $data, $timeReported)
	{
		//Add queue time
		$queueTime = round((microtime(true) - $timeReported) * 1000);
		$data['qt'] = $queueTime;

		//Send request
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://ssl.google-analytics.com/collect');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);

		curl_close ($ch);
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 * @param BaseModel $model
	 */
	protected static function addValidationRules(&$validator, $model)
	{
		//General
		$validator->value('version')->required();
		$validator->value('trackingId')->required();
		$validator->value('anonymizeIp')->boolean();
		$validator->value('queueTime')->integer();
		//User
		$validator->value('clientId')->required();
		//Session
		$validator->value('ipOverride')->ip();
		//Traffic Sources
		$validator->value('documentReferrer')->url();
		//System Info
		$validator->value('javaEnabled')->boolean();
		//Hit
		$validator->value('hitType')->required();
		$validator->value('nonInteractionHit')->boolean();
		//Content Information
		$validator->value('documentLocationUrl')->url();
		$validator->value('screenName')->requiredIf('hitType', array('screenview'));
		//App Tracking
		$validator->value('applicationName')->requiredIf('dataSource', array('app'));
		//Event Tracking
		$validator->value('eventCategory')->requiredIf('hitType', array('event'));
		$validator->value('eventAction')->requiredIf('hitType', array('event'));
		$validator->value('eventValue')->integer();
		//E-Commerce
		$validator->value('transactionId')->requiredIf('hitType', array('transaction', 'item'));
		$validator->value('transactionRevenue')->numeric();
		$validator->value('transactionShipping')->numeric();
		$validator->value('transactionTax')->numeric();
		$validator->value('itemName')->requiredIf('hitType', array('item'));
		$validator->value('itemPrice')->numeric();
		$validator->value('itemQuantity')->integer();
		//Enhanced E-Commerce
		$validator->value('productSku')->sometimes(function($input) {
			$value = $input->productSku;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productName')->sometimes(function($input) {
			$value = $input->productName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productBrand')->sometimes(function($input) {
			$value = $input->productBrand;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productCategory')->sometimes(function($input) {
			$value = $input->productCategory;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productVariant')->sometimes(function($input) {
			$value = $input->productVariant;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productPrice')->sometimes(function($input) {
			$value = $input->productPrice;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_numeric($value[1]);
		});
		$validator->value('productQuantity')->sometimes(function($input) {
			$value = $input->productQuantity;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
		$validator->value('productCouponCode')->sometimes(function($input) {
			$value = $input->productCouponCode;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productPosition')->sometimes(function($input) {
			$value = $input->productPosition;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
		$validator->value('productCustomDimension')->sometimes(function($input) {
			$value = $input->productCustomDimension;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productCustomMetric')->sometimes(function($input) {
			$value = $input->productCustomMetric;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]);
		});
		$validator->value('revenue')->numeric();
		$validator->value('tax')->numeric();
		$validator->value('shipping')->numeric();
		$validator->value('checkoutStep')->integer();
		$validator->value('productImpressionListName')->sometimes(function($input) {
			$value = $input->productImpressionListName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('productImpressionSku')->sometimes(function($input) {
			$value = $input->productImpressionSku;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productImpressionName')->sometimes(function($input) {
			$value = $input->productImpressionName;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productImpressionBrand')->sometimes(function($input) {
			$value = $input->productImpressionBrand;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productImpressionCategory')->sometimes(function($input) {
			$value = $input->productImpressionCategory;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productImpressionVariant')->sometimes(function($input) {
			$value = $input->productImpressionVariant;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200;
		});
		$validator->value('productImpressionPosition')->sometimes(function($input) {
			$value = $input->productImpressionPosition;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]);
		});
		$validator->value('productImpressionPrice')->sometimes(function($input) {
			$value = $input->productImpressionPrice;
			return is_array($value) && count($value) == 3 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_numeric($value[2]);
		});
		$validator->value('productImpressionCustomDimension')->sometimes(function($input) {
			$value = $input->productImpressionCustomDimension;
			return is_array($value) && count($value) == 4 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]) && 1 <= $value[2] && $value[2] <= 200;
		});
		$validator->value('productImpressionCustomMetric')->sometimes(function($input) {
			$value = $input->productImpressionCustomMetric;
			return is_array($value) && count($value) == 4 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]) && 1 <= $value[1] && $value[1] <= 200
				&& is_int_($value[2]) && 1 <= $value[2] && $value[2] <= 200
				&& is_int_($value[3]);
		});
		$validator->value('promotionId')->sometimes(function($input) {
			$value = $input->promotionId;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('promotionName')->sometimes(function($input) {
			$value = $input->promotionName;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('promotionCreative')->sometimes(function($input) {
			$value = $input->promotionCreative;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('promotionPosition')->sometimes(function($input) {
			$value = $input->promotionPosition;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		//Social Interactions
		$validator->value('socialNetwork')->requiredIf('hitType', array('social'));
		$validator->value('socialAction')->requiredIf('hitType', array('social'));
		$validator->value('socialActionTarget')->requiredIf('hitType', array('social'))->url();
		//Timing
		$validator->value('userTimingCategory')->requiredIf('hitType', array('timing'));
		$validator->value('userTimingVariableName')->requiredIf('hitType', array('timing'));
		$validator->value('userTimingTime')->requiredIf('hitType', array('timing'))->integer();
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
		$validator->value('customDimension')->sometimes(function($input) {
			$value = $input->customDimension;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200;
		});
		$validator->value('customMetric')->sometimes(function($input) {
			$value = $input->customMetric;
			return is_array($value) && count($value) == 2 && is_int_($value[0]) && 1 <= $value[0] && $value[0] <= 200
				&& is_int_($value[1]);
		});
	}
}
