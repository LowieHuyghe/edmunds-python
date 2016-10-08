<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Localization\Commands;

use Edmunds\Bases\Commands\BaseCommand;
use Edmunds\Localization\Translator;
use Edmunds\Localization\Models\Translation;
use Edmunds\Registry;

/**
 * The command syncing all the translations in the project.
 */
class SyncCommand extends BaseCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lang:sync';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Syncing all the translations in the project";

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->sync();

		$this->generate();
	}

	/**
	 * Sync all the translations in the database
	 */
	protected function sync()
	{
		$this->info('Syncing the translations.');

		Registry::db()->builder('translations')->update(array('used' => 0));

		$files = $this->getAllFiles(base_path());
		$regexs = array(
			"/trans\(\"((.|\n)*?)\"(.|\n)*?\)/",
			"/trans\('((.|\n)*?)'(.|\n)*?\)/",
		);

		$progress = $this->output->createProgressBar(count($files));
		foreach ($files as $file)
		{
			foreach ($regexs as $regex)
			{
				$matches = array();
				if (preg_match_all($regex, file_get_contents($file), $matches))
				{
					foreach ($matches[1] as $match)
					{
						$key = Translator::getKey($match);

						$translation = Translation::where('hash', '=', $key)->first();
						if ($translation)
						{
							++$translation->used;
						}
						else
						{
							$translation = new Translation();
							$translation->hash = $key;
							$translation->original = $match;
							$translation->used = 1;
						}
						$translation->save();

						$progress->advance();
					}
				}
			}
		}

		$progress->finish();

		//Do the defaults
		foreach ($this->getDefaults() as $key => $value)
		{
			$key = Translator::getKey($key);

			$translation = Translation::where('hash', '=', $key)->first();
			if ($translation)
			{
				++$translation->used;
			}
			else
			{
				$translation = new Translation();
				$translation->hash = $key;
				$translation->original = $value;
				$translation->used = 1;
			}
			$translation->save();
		}

		$this->info("\nCompleted syncing translations.");
	}

	/**
	 * Generate all the translation files
	 */
	protected function generate()
	{
		$this->info('Generating the translation-files.');

		$translations = Translation::where('used', '>', 0)->get();

		//Fetch all translations
		$all = array();

		$translations->each(function($translation) use (&$all)
		{
			$group = Translator::getGroup($translation->hash);

			foreach ($translation->getAttributes() as $lang => $value)
			{
				if (strlen($lang) != 2 || !$value)
				{
					continue;
				}
				$all[$lang][$group][$translation->hash] = $value;
			}
		});

		//Process everything and save
		$langDir = Translator::getLangPath();
		foreach ($all as $lang => $langValue)
		{
			foreach ($langValue as $group => $groupValue)
			{
				$text = '<?php return array(';

				foreach ($groupValue as $hash => $value)
				{
					$text .= "'$hash' => \"$value\",";
				}

				$text .= ');';

				//Put contents in files
				$dir = "$langDir/$lang";
				$file = "$dir/$group.php";
				if ( ! file_exists($dir))
				{
					mkdir($dir, 0777, true);
				}
				file_put_contents($file, $text);
			}
		}

		$this->info('Completed generating the translation-files.');
	}

	/**
	 * Get all the files with potential translations
	 * @param string $dir
	 * @param array &$results
	 * @return array
	 */
	protected function getAllFiles($dir, &$results = array())
	{
		$files = scandir($dir);

		foreach($files as $key => $value)
		{
			if (strpos($value, '.') === 0)
			{
				continue;
			}

			$path = realpath("$dir/$value");

			if( ! is_dir($path))
			{
				$results[] = $path;
			}
			elseif ( ! in_array($path, array(base_path('vendor'), base_path('node_modules'), storage_path(), Translator::getLangPath())))
			{
				$this->getAllFiles($path, $results);
			}
		}

		return $results;
	}

	/**
	 * Add some default values to the translations
	 * @return array
	 */
	protected function getDefaults()
	{
		//Load defaults
		$files = array_merge(
			$this->getAllFiles(EDMUNDS_BASE_PATH . '/resources/lang'),
			$this->getAllFiles(base_path('resources/lang'))
		);

		//Load all translations
		$translations = array();
		foreach ($files as $file)
		{
			$translations = array_merge($translations, require($file));
		}

		return $translations;
	}

}