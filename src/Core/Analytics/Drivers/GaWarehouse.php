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

namespace Core\Analytics\Drivers;

use Core\Analytics\Drivers\BaseWarehouse;

/**
 * The piwik warehouse driver
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class GaWarehouse extends BaseWarehouse
{
	/**
	 * The api-url
	 * @var string
	 */
	protected static $apiUrl = 'https://ssl.google-analytics.com/collect';

	/**
	 * Parameter mapping
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
	 * Flush all the saved up logs
	 */
	public function flush()
	{
		// queue each log
		foreach ($this->logs as $log)
		{
			$data = $this->getAttributesFromLog($log);

			$this->queue(array(get_called_class(), 'send'), array($data, microtime(true)));
		}
	}

	/**
	 * Send it all!
	 * @param  array $data
	 * @param  float $timeReported
	 */
	public static function send($data, $timeReported)
	{
		// setup header
		$header = array('Content-type: application/x-www-form-urlencoded');

		//Add queue time
		$queueTime = round((microtime(true) - $timeReported) * 1000);
		$data['qt'] = $queueTime;

		// send request
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, static::$apiUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);

		curl_close ($ch);
	}
}
