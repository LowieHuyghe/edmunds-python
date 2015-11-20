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

	/**
	 * Add some default values to the translations
	 * @return array
	 */
	protected function getDefaults()
	{
		return array
		(
			'validation.accepted'				=> 'The {attribute} must be accepted.',
			'validation.active_url'				=> 'The {attribute} is not a valid URL.',
			'validation.after'					=> 'The {attribute} must be a date after {date}.',
			'validation.alpha'					=> 'The {attribute} may only contain letters.',
			'validation.alpha_dash'				=> 'The {attribute} may only contain letters, numbers, and dashes.',
			'validation.alpha_num'				=> 'The {attribute} may only contain letters and numbers.',
			'validation.array'					=> 'The {attribute} must be an array.',
			'validation.before'					=> 'The {attribute} must be a date before {date}.',
			'validation.between.numeric'		=> 'The {attribute} must be between {min} and {max}.',
			'validation.between.file'			=> 'The {attribute} must be between {min} and {max} kilobytes.',
			'validation.between.string'			=> 'The {attribute} must be between {min} and {max} characters.',
			'validation.between.array'			=> 'The {attribute} must have between {min} and {max} items.',
			'validation.boolean'				=> 'The {attribute} field must be true or false.',
			'validation.confirmed'				=> 'The {attribute} confirmation does not match.',
			'validation.date'					=> 'The {attribute} is not a valid date.',
			'validation.date_format'			=> 'The {attribute} does not match the format {format}.',
			'validation.different'				=> 'The {attribute} and {other} must be different.',
			'validation.digits'					=> 'The {attribute} must be {digits} digits.',
			'validation.digits_between'			=> 'The {attribute} must be between {min} and {max} digits.',
			'validation.email'					=> 'The {attribute} must be a valid email address.',
			'validation.exists'					=> 'The selected {attribute} is invalid.',
			'validation.filled'					=> 'The {attribute} field is required.',
			'validation.image'					=> 'The {attribute} must be an image.',
			'validation.in'						=> 'The selected {attribute} is invalid.',
			'validation.integer'				=> 'The {attribute} must be an integer.',
			'validation.ip'						=> 'The {attribute} must be a valid IP address.',
			'validation.json'					=> 'The {attribute} must be a valid JSON string.',
			'validation.max.numeric'			=> 'The {attribute} may not be greater than {max}.',
			'validation.max.file'				=> 'The {attribute} may not be greater than {max} kilobytes.',
			'validation.max.string'				=> 'The {attribute} may not be greater than {max} characters.',
			'validation.max.array'				=> 'The {attribute} may not have more than {max} items.',
			'validation.mimes'					=> 'The {attribute} must be a file of type: {value}s.',
			'validation.min.numeric'			=> 'The {attribute} must be at least {min}.',
			'validation.min.file'				=> 'The {attribute} must be at least {min} kilobytes.',
			'validation.min.string'				=> 'The {attribute} must be at least {min} characters.',
			'validation.min.array'				=> 'The {attribute} must have at least {min} items.',
			'validation.not_in'					=> 'The selected {attribute} is invalid.',
			'validation.numeric'				=> 'The {attribute} must be a number.',
			'validation.regex'					=> 'The {attribute} format is invalid.',
			'validation.required'				=> 'The {attribute} field is required.',
			'validation.required_if'			=> 'The {attribute} field is required when {other} is {value}.',
			'validation.required_with'			=> 'The {attribute} field is required when {value}s is present.',
			'validation.required_with_all'		=> 'The {attribute} field is required when {value}s is present.',
			'validation.required_without'		=> 'The {attribute} field is required when {value}s is not present.',
			'validation.required_without_all'	=> 'The {attribute} field is required when none of {value}s are present.',
			'validation.same'					=> 'The {attribute} and {other} must match.',
			'validation.size.numeric'			=> 'The {attribute} must be {size}.',
			'validation.size.file'				=> 'The {attribute} must be {size} kilobytes.',
			'validation.size.string'			=> 'The {attribute} must be {size} characters.',
			'validation.size.array'				=> 'The {attribute} must contain {size} items.',
			'validation.string'					=> 'The {attribute} must be a string.',
			'validation.timezone'				=> 'The {attribute} must be a valid zone.',
			'validation.unique'					=> 'The {attribute} has already been taken.',
			'validation.url'					=> 'The {attribute} format is invalid.',
		);
	}

}