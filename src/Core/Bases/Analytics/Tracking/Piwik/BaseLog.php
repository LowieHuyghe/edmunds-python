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

namespace Core\Bases\Analytics\Tracking\Piwik;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Localization\DateTime;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for Piwik logs
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 *	// required
 * @property integer $siteId
 * @property boolean $record
 * @property string $url
	// recommended
 * @property string $actionName
 * @property string $visitorIdUnique
 * @property string $cacheBuster
 * @property integer $version
	// user info
 * @property string $userReferrer
 * @property string $userCustomVariablesVisitScope
 * @property integer $userVisitCount
 * @property integer $userVisitPreviousTime
 * @property integer $userVisitFirstTime
 * @property string $userCampaignName
 * @property string $userCampaignKeywords
 * @property string $userResolution
 * @property integer $userTimeLocalHour
 * @property integer $userTimeLocalMinute
 * @property integer $userTimeLocalSecond
 * @property boolean $userPluginFlash
 * @property boolean $userPluginJava
 * @property boolean $userPluginDirector
 * @property boolean $userPluginQuicktime
 * @property boolean $userPluginRealPlayer
 * @property boolean $userPluginPdf
 * @property boolean $userPluginWindowsMedia
 * @property boolean $userPluginGears
 * @property boolean $userPluginSilverlight
 * @property boolean $userCookieSupport
 * @property string $userUserAgent
 * @property string $userAcceptLanguage
 * @property integer $userId
 * @property string $userVisitorId
 * @property boolean $userNewVisit
	// optional action info
 * @property string $actionCustomVariablesPageScope
 * @property string $actionExternalUrl
 * @property string $actionDownload
 * @property string $actionSearchKeyword
 * @property string $actionSearchCategory
 * @property string $actionSearchResults
 * @property integer $actionGoalId
 * @property double $actionRevenue
 * @property integer $actionRenderTime
 * @property string $actionCharset
	// other parameters (require token_auth)
 * @property string $otherAuthToken
 * @property string $otherAuthIp
 * @property integer $otherAuthTime
 * @property string $otherAuthCountryCode
 * @property string $otherAuthRegionCode
 * @property string $otherAuthCityName
 * @property double $otherAuthLatitude
 * @property double $otherAuthLongitude
	// other parameters
 * @property boolean $otherSendImage
 * @property boolean $otherTrackBots
 *
 */
class BaseLog extends \Core\Bases\Analytics\Tracking\BaseLog
{
	/**
	 * The mapping of the parameters for the call
	 * @var array
	 */
	protected $parameterMapping = array(
		// required
		'siteId' => 'idsite',
		'record' => 'rec',
		'url' => 'url',
		// recommended
		'actionName' => 'action_name',
		'visitorIdUnique' => '_id',
		'cacheBuster' => 'rand',
		'version' => 'apiv',
		// user info
		'userReferrer' => 'urlref',
		'userCustomVariablesVisitScope' => '_cvar',
		'userVisitCount' => '_idvar',
		'userVisitPreviousTime' => '_viewts',
		'userVisitFirstTime' => '_idts',
		'userCampaignName' => '_rcn',
		'userCampaignKeywords' => '_rck',
		'userResolution' => 'res',
		'userTimeLocalHour' => 'h',
		'userTimeLocalMinute' => 'm',
		'userTimeLocalSecond' => 's',
		'userPluginFlash' => 'fla',
		'userPluginJava' => 'java',
		'userPluginDirector' => 'dir',
		'userPluginQuicktime' => 'qt',
		'userPluginRealPlayer' => 'realp',
		'userPluginPdf' => 'pdf',
		'userPluginWindowsMedia' => 'wma',
		'userPluginGears' => 'gears',
		'userPluginSilverlight' => 'ag',
		'userCookieSupport' => 'cookie',
		'userUserAgent' => 'ua',
		'userAcceptLanguage' => 'lang',
		'userId' => 'uid',
		'userVisitorId' => 'cid',
		'userNewVisit' => 'new_visit',
		// optional action info
		'actionCustomVariablesPageScope' => 'cvar',
		'actionExternalUrl' => 'link',
		'actionDownload' => 'download',
		'actionSearchKeyword' => 'search',
		'actionSearchCategory' => 'search_cat',
		'actionSearchResults' => 'search_count',
		'actionGoalId' => 'idgoal',
		'actionRevenue' => 'revenue',
		'actionRenderTime' => 'gt_ms',
		'actionCharset' => 'cs',
		// optional event info
		'eventCategory' => 'e_c',
		'eventAction' => 'e_a',
		'eventName' => 'e_n',
		'eventValue' => 'e_v',
		// optional content info
		'contentName' => 'c_n',
		'contentPiece' => 'c_p',
		'contentTarget' => 'c_t',
		'contentInteraction' => 'c_i',
		// optional ecommerce info
		'ecommerceId' => 'ec_id',
		'ecommerceItems' => 'ec_items',
		'ecommerceRevenue' => 'revenue',
		'ecommerceSubtotal' => 'ec_st',
		'ecommerceTax' => 'ec_tx',
		'ecommerceShippingCost' => 'ec_sh',
		'ecommerceDiscount' => 'ec_dt',
		'ecommercePreviousTime' => '_ects',
		// other parameters (require token_auth)
		'otherAuthToken' => 'token_auth',
		'otherAuthIp' => 'cip',
		'otherAuthTime' => 'cdt',
		'otherAuthCountryCode' => 'country',
		'otherAuthRegionCode' => 'region',
		'otherAuthCityName' => 'city',
		'otherAuthLatitude' => 'lat',
		'otherAuthLongitude' => 'long',
		// other parameters
		'otherSendImage' => 'send_image',
		'otherTrackBots' => 'bots',
	);

	/** @var string The api-url */
	protected static $apiUrl = 'https://stats.lowiehuyghe.com/piwik.php';

	/** @var array All the api requests bundled */
	protected static $requests = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Set the version and tracking info
		$this->version = config('analytics.piwik.version');
		$this->siteId = config('analytics.piwik.siteid');
		$this->cacheBuster = rand(0, 2000000000);
		$this->record = true;

		//Only if request is set
		if ($request = Request::getInstance())
		{
			$visitor = Visitor::getInstance();
			$visitorId = substr(str_replace('-', '', $visitor->id), 0, 16); // visitor id must only be 16 long and only hexdec chars

			//Assign default values
			$this->visitorIdUnique = $visitorId;

			$this->url = $request->fullUrl;
			$this->actionCharset = "UTF-8";

			$this->otherAuthIp = $request->ip;

			$this->userUserAgent = $visitor->context->userAgent;
			if ($visitor->loggedIn) $this->userId = $visitor->user->id;
			$this->userAcceptLanguage = $visitor->context->acceptLanguage;
			$this->userVisitorId = $visitorId;
			if ($request->referrer) $this->userReferrer = $request->referrer;
			$time = new DateTime();
			$this->userTimeLocalHour = $time->hour;
			$this->userTimeLocalMinute = $time->minute;
			$this->userTimeLocalSecond = $time->second;
		}

		//$this->userCustomVariablesVisitScope = array_merge($this->userCustomVariablesVisitScope ?: array(), array(array('Environment', config('app.env', false))));
	}

	/**
	 * Report the log
	 * @throws \Exception
	 */
	public function report()
	{
		// add report time
		$time = new DateTime(null, 'UTC');
		$this->otherAuthTime = $time->timestamp;

		// fetch data
		self::$requests[] = '?' . http_build_query($this->getAttributesMapped());
	}

	/**
	 * Flush all the saved up reports
	 */
	public static function flushReports()
	{
		//Setup header
		$header = array('Content-type: application/x-www-form-urlencoded');

		// fetch data
		$data = array(
			'requests' => self::$requests,
			'token_auth' => config('analytics.piwik.token'),
		);

		Registry::queue()->dispatch(array(get_called_class(), 'send'), array(
			$header, count($data), json_encode($data), microtime(true),
		), Queue::QUEUE_LOG);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		// required
		$this->validator->value('siteId')->required()->integer();
		$this->validator->value('record')->required()->boolean();
		$this->validator->value('url')->required();

		// recommended
		// $this->validator->value('actionName');
		$this->validator->value('visitorIdUnique')->required()->min(16)->max(16);
		$this->validator->value('cacheBuster')->required();
		$this->validator->value('version')->required()->integer();

		// user info
		$this->validator->value('userReferrer')->url();
		// $this->validator->value('userCustomVariablesVisitScope');
		$this->validator->value('userVisitCount')->integer();
		$this->validator->value('userVisitPreviousTime')->integer();
		$this->validator->value('userVisitFirstTime')->integer();
		// $this->validator->value('userCampaignName');
		// $this->validator->value('userCampaignKeywords');
		// $this->validator->value('userResolution');
		$this->validator->value('userTimeLocalHour')->integer();
		$this->validator->value('userTimeLocalMinute')->integer();
		$this->validator->value('userTimeLocalSecond')->integer();
		$this->validator->value('userPluginFlash')->boolean();
		$this->validator->value('userPluginJava')->boolean();
		$this->validator->value('userPluginDirector')->boolean();
		$this->validator->value('userPluginQuicktime')->boolean();
		$this->validator->value('userPluginRealPlayer')->boolean();
		$this->validator->value('userPluginPdf')->boolean();
		$this->validator->value('userPluginWindowsMedia')->boolean();
		$this->validator->value('userPluginGears')->boolean();
		$this->validator->value('userPluginSilverlight')->boolean();
		$this->validator->value('userCookieSupport')->boolean();
		// $this->validator->value('userUserAgent');
		// $this->validator->value('userAcceptLanguage');
		$this->validator->value('userId')->integer();
		$this->validator->value('userVisitorId')->min(16)->max(16);
		$this->validator->value('userNewVisit')->boolean();

		// optional action info
		// $this->validator->value('actionCustomVariablesPageScope');
		$this->validator->value('actionExternalUrl')->url();
		// $this->validator->value('actionDownload');
		// $this->validator->value('actionSearchKeyword');
		// $this->validator->value('actionSearchCategory');
		// $this->validator->value('actionSearchResults');
		$this->validator->value('actionGoalId')->integer();
		$this->validator->value('actionRevenue')->numeric();
		$this->validator->value('actionRenderTime')->integer();
		// $this->validator->value('actionCharset');

		// optional event info
		$this->validator->value('eventCategory')->requiredWith(array(
			'eventAction',
			'eventName',
			'eventValue',
		));
		$this->validator->value('eventAction')->requiredWith(array(
			'eventCategory',
			'eventName',
			'eventValue',
		));
		$this->validator->value('eventName')->requiredWith(array(
			'eventCategory',
			'eventAction',
			'eventValue',
		));
		$this->validator->value('eventValue')->requiredWith(array(
			'eventCategory',
			'eventAction',
			'eventName',
		));

		// optional content info
		// $this->validator->value('contentName');
		// $this->validator->value('contentPiece');
		// $this->validator->value('contentTarget');
		// $this->validator->value('contentInteraction');

		// optional ecommerce info
		$this->validator->value('ecommerceId')->requiredWith(array('ecommerceRevenue'));
		// $this->validator->value('ecommerceItems');
		$this->validator->value('ecommerceRevenue')->numeric()->requiredWith(array('ecommerceId'));
		$this->validator->value('ecommerceSubtotal')->numeric();
		$this->validator->value('ecommerceTax')->numeric();
		$this->validator->value('ecommerceShippingCost')->numeric();
		$this->validator->value('ecommerceDiscount')->numeric();
		$this->validator->value('ecommercePreviousTime')->integer();

		// other parameters (require token_auth)
		$this->validator->value('otherAuthToken')->requiredWith(array(
			'otherAuthIp',
			'otherAuthTime',
			'otherAuthCountryCode',
			'otherAuthRegionCode',
			'otherAuthCityName',
			'otherAuthLatitude',
			'otherAuthLongitude',
		));
		$this->validator->value('otherAuthIp')->ip();
		$this->validator->value('otherAuthTime')->integer();
		// $this->validator->value('otherAuthCountryCode');
		// $this->validator->value('otherAuthRegionCode');
		// $this->validator->value('otherAuthCityName');
		$this->validator->value('otherAuthLatitude')->numeric();
		$this->validator->value('otherAuthLongitude')->numeric();

		// other parameters
		$this->validator->value('otherSendImage')->boolean();
		$this->validator->value('otherTrackBots')->boolean();
	}
}
