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

namespace Core\Helpers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Core\Models\Translation;
use Core\Structures\Client\Visitor;
use Core\Structures\Http\Request;
use Symfony\Component\Translation\MessageSelector;

/**
 * The helper responsible for translations
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class TranslationHelper extends BaseHelper
{
	/**
	 * Instance of the localization-helper
	 * @var TranslationHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the localization-helper
	 * @return TranslationHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new TranslationHelper();
		}

		return self::$instance;
	}

	/**
	 * The visitor
	 * @var Visitor
	 */
	protected $visitor;

	/**
	 * The selector of the messages
	 * @var MessageSelector
	 */
	protected $selector;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->visitor = Visitor::getInstance();
	}

	/**
	 * Get the translation for a given key.
	 *
	 * @param  string  $id
	 * @param  array   $parameters
	 * @param  string  $locale
	 * @return string
	 */
	public function trans($id, array $parameters = [], $locale = null)
	{
		return $this->get($id, $parameters, $locale);
	}
	/**
	 * Get a translation according to an integer value.
	 *
	 * @param  string  $id
	 * @param  int     $number
	 * @param  array   $parameters
	 * @param  string  $locale
	 * @return string
	 */
	public function transChoice($id, $number, array $parameters = [], $locale = null)
	{
		$line = $this->get($id, $parameters, $locale);
		$parameters['count'] = $number;
		$locale = $locale ?: $this->visitor->localization->locale ?: $this->visitor->localization->fallback;
		return $this->makeReplacements($this->getSelector()->choose($line, $number, $locale), $parameters);
	}

	/**
	 * Get the translation for the given key.
	 *
	 * @param  string  $key
	 * @param  array   $replace
	 * @param  string  $locale
	 * @return string
	 */
	protected function get($key, array $replace = [], $locale)
	{
		//Fetch the translation
		$hash = md5($key);
		$translation = Translation::where('hash', '=', $hash)->first();

		//Create a new one if not exist
		if (!$translation)
		{
			$translation = new Translation();
			$translation->hash = $hash;
			$translation->original = $key;

			//Save if not in local-mode
			if (Request::getInstance()->isProductionEnvironment())
			{
				$translation->save();
			}
		}

		//Get the line
		$line = null;
		if ($locale && isset($translation->$locale))
		{
			$line = $translation->$locale;
		}
		$locale = $this->visitor->localization->locale;
		if (!$line && $locale && $translation->$locale)
		{
			$line = $translation->$locale;
		}
		$locale = $this->visitor->localization->fallback;
		if (!$line && $locale && $translation->$locale)
		{
			$line = $translation->$locale;
		}
		elseif (!$line)
		{
			$line = $translation->original;
		}

		return $this->makeReplacements($line, $replace);
	}

	/**
	 * Make the place-holder replacements on a line.
	 *
	 * @param  string  $line
	 * @param  array   $replace
	 * @return string
	 */
	protected function makeReplacements($line, array $replace)
	{
		$replace = $this->sortReplacements($replace);
		foreach ($replace as $key => $value) {
			$line = str_replace(':'.$key, $value, $line);
		}
		return $line;
	}
	/**
	 * Sort the replacements array.
	 *
	 * @param  array  $replace
	 * @return array
	 */
	protected function sortReplacements(array $replace)
	{
		return (new Collection($replace))->sortBy(function ($value, $key) {
			return mb_strlen($key) * -1;
		});
	}

	/**
	 * Get the message selector instance.
	 * @return MessageSelector
	 */
	public function getSelector()
	{
		if (!isset($this->selector)) {
			$this->selector = new MessageSelector;
		}
		return $this->selector;
	}

}
