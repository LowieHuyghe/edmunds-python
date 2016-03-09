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

namespace Core\Localization;

use Core\Analytics\Tracking\ErrorLog;
use Core\Auth\Models\Gender;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Response;
use Core\Localization\Exceptions\TranslationException;
use Core\Localization\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Translation\FileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * The helper responsible for translations
 * Example:
 *   Your are a ~~gender{__login}||man|woman~~
 *   You have ~~plural{appels}||an appels|{appels} appels~~
 *   You have ~~plural{pears}||{1}a pear|]1,Inf[multiple pears~~
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Translator extends BaseStructure implements TranslatorInterface
{
	/**
	 * Instance of the localization-helper
	 * @var Translator
	 */
	private static $instance;

	/**
	 * Fetch instance of the localization-helper
	 * @return Translator
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Translator();
		}

		return self::$instance;
	}

	/**
	 * The selector of the messages
	 * @var MessageSelector
	 */
	protected $selector;

	/**
	 * The loader implementation.
	 *
	 * @var \Illuminate\Translation\LoaderInterface
	 */
	protected $loader;

	/**
	 * The array of loaded translation groups.
	 *
	 * @var array
	 */
	protected $loaded = [];

	/**
	 * The locales used for translations
	 * @var array
	 */
	protected $locales;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->loader = new FileLoader(app('files'), self::getLangPath());

		parent::__construct();
	}

	/**
	 * Get the path where the language files are stored
	 * @return string
	 */
	public static function getLangPath()
	{
		return storage_path('app/lang');
	}

	/**
	 * Get the possible locales
	 * @param string $locale
	 * @return array
	 */
	protected function getLocales($locale = null)
	{
		//Set the default locales
		if (!isset($this->locales))
		{
			$localization = Visitor::getInstance()->localization;

			$this->locales = array_unique(array_filter(array(
				$localization->locale,
				$localization->fallback,
			)));
		}
		$locales = $this->locales;

		//Add one custom locale if necessary
		if ($locale)
		{
			$locales[] = $locale;
			$locales = array_unique(array_filter($locales));
		}

		return $locales;
	}

	/**
	 * Get the group where to fetch the translation
	 * @param string $key
	 * @return string
	 */
	public static function getGroup($key)
	{
		return $key[0];
	}

	/**
	 * Get the key used for a translation
	 * @param string $message
	 * @return string
	 */
	public static function getKey($message)
	{
		return md5($message);
	}

	/**
	 * Get the translation for a given key.
	 *
	 * @param string  $message
	 * @param array $parameters
	 * @param string  $locale
	 * @param bool $onlyReplacements
	 * @return string
	 */
	public function trans($message, array $parameters = array(), $domain = null, $locale = null, $onlyReplacements = false)
	{
		$namespace = '*';
		$key = self::getKey($message);
		$group = self::getGroup($key);

		try
		{
			if (self::isEnabled() && !$onlyReplacements)
			{
				$locales = $this->getLocales($locale);
				foreach ($locales as $potLocale)
				{
					$this->load($namespace, $group, $potLocale);
					$translated = Arr::get($this->loaded[$namespace][$group][$potLocale], $key);
					if ($translated && $translated != $key)
					{
						return $this->makeReplacements($key, $translated, $parameters, $potLocale);
					}
				}
			}

			return $this->makeReplacements($key, $message, $parameters, $locale);
		}
		catch(TranslationException $e)
		{
			$log = new ErrorLog();
			$log->exception = $e;
			$log->type = 'TranslationError';
			$log->log();
		}
	}

	/**
	 * Make the place-holder replacements on a line.
	 *
	 * @param  string $key
	 * @param  string $line
	 * @param  array $replace
	 * @param  string $locale
	 * @return string
	 */
	protected function makeReplacements($key, $line, array $replace, $locale)
	{
		$replace = $this->sortReplacements($replace);

		$regex = "/~~([pPgG]){([^~\|{}]+?)}\|\|((?!~~)(.|\n)+?\|(?!~~)(.|\n)+?)~~/";

		$count = 1;
		while($count > 0)
		{
			$line = preg_replace_callback($regex, function(array $matches) use ($replace)
			{
				$type = strtolower($matches[1]);
				$value = $this->getValue($matches[2], $replace);
				$message = $matches[3];

				switch($type)
				{
					case 'g':
						$parts = explode('|', $message);
						if ($value->gender->id == Gender::MALE)
							$message = $parts[0];
						else
							$message = $parts[1];
						break;
					case 'p':
						$message = $this->getSelector()->choose($message, $value, 'nl');
						break;
					default:
						throw new TranslationException("Unknown function '$type' used (lng: $locale, key: $key)");
				}

				return $message;
			}, $line, -1, $count);
		}

		//Check if delimiters are still used
		if (($pos = strpos($line, '~~')) !== false)
		{
			throw new TranslationException("Delimiter still present on position $pos (lng: $locale, key: $key)");
		}

		$line = preg_replace_callback("/{([^{}]+?)}/", function (array $matches) use ($replace)
		{
			$value = $matches[1];
			return $this->getValue($value, $replace);
		}, $line);

		return $line;
	}

	/**
	 * Sort the replacements array.
	 *
	 * @param  array $replace
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
	protected function getSelector()
	{
		if (!isset($this->selector)) {
			$this->selector = new MessageSelector;
		}
		return $this->selector;
	}

	/**
	 * Get the value of something
	 * @param string $name
	 * @param array $replace
	 * @return string
	 */
	protected function getValue($name, $replace)
	{
		$parts = explode('.', $name);
		$part = array_shift($parts);

		$value = $replace[$part];

		foreach ($parts as $part)
		{
			if (is_array($value))
			{
				$value = $value[$part];
			}
			else
			{
				$query = 'get' . ucfirst($part);
				$value = $value->$query();
			}
		}

		return $value;
	}

	/**
	 * Load the specified language group.
	 *
	 * @param  string  $namespace
	 * @param  string  $group
	 * @param  string  $locale
	 * @return void
	 */
	public function load($namespace, $group, $locale)
	{
		if ($this->isLoaded($namespace, $group, $locale)) {
			return;
		}

		// The loader is responsible for returning the array of language lines for the
		// given namespace, group, and locale. We'll set the lines in this array of
		// lines that have already been loaded so that we can easily access them.
		$lines = $this->loader->load($locale, $group, $namespace);

		$this->loaded[$namespace][$group][$locale] = $lines;
	}

	/**
	 * Determine if the given group has been loaded.
	 *
	 * @param  string  $namespace
	 * @param  string  $group
	 * @param  string  $locale
	 * @return bool
	 */
	protected function isLoaded($namespace, $group, $locale)
	{
		return isset($this->loaded[$namespace][$group][$locale]);
	}

    /**
     * @deprecated
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null) {}

    /**
     * @deprecated
     */
    public function setLocale($locale) {}

    /**
     * @deprecated
     */
    public function getLocale() {}

	/**
	 * Check if translations is enabled
	 * @return boolean
	 */
	public static function isEnabled()
	{
		return config('app.translations.enabled', true);
	}

}