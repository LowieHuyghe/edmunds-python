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

namespace Core\Commands\Translation;
use Core\Bases\Commands\BaseCommand;
use Core\Io\Translator;
use Core\Models\Translation;
use Core\Registry\Registry;

/**
 * The command syncing all the translations in the project.
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
		$regex = "/trans\(\"(.*?)\"\)/";

		$progress = $this->output->createProgressBar(count($files));
		foreach ($files as $file)
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

		$progress->finish();

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
				$dir = $langDir.DIRECTORY_SEPARATOR.$lang;
				$file = $dir.DIRECTORY_SEPARATOR.$group.'.php';
				if (!file_exists($dir))
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

			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);

			if(!is_dir($path))
			{
				$results[] = $path;
			}
			else if(!in_array($path, array(base_path('vendor'), storage_path(), Translator::getLangPath())))
			{
				$this->getAllFiles($path, $results);
			}
		}

		return $results;
	}

}