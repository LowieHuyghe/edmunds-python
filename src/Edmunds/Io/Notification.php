<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Io;

use Edmunds\Bases\Structures\BaseStructure;
use Faker\Generator;
use Illuminate\Support\Collection;

/**
 * The structure for notifications
 *
 * @property string $title
 * @property string $body
 * @property string $url
 * @property array $options
 * @property string $type
 * @property string $subtype
 */
class Notification extends BaseStructure
{
	/**
	 * All the notifications
	 * @var Notification[]
	 */
	private static $all = array();

	/**
	 * Return all the notifications and filter if wanted
	 * @param string $type
	 * @param string $subtype
	 * @return Collection
	 */
	public static function all($type = null, $subtype = null)
	{
		//If nothing specified, just return all
		if ( ! $type && !$subtype)
		{
			return collect(self::$all);
		}

		//Filter
		$notifications = array();
		foreach (self::$all as $notification)
		{
			if ($type && $notification->type != $type)
			{
				continue;
			}
			if ($subtype && $notification->subtype != $subtype)
			{
				continue;
			}
			$notifications[] = $notification;
		}

		return collect($notifications);
	}

	/**
	 * Clear all the notifications
	 */
	public static function clear()
	{
		self::$all = array();
	}

	/**
	 * Save the instance of the notification
	 */
	public function save()
	{
		self::$all[] = $this;
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$validator->rule('title')->required();
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'title' => $faker->realText(100, 5),
			'body' => $faker->realText(100, 5),
			'url' => "/url",
			'options' => array(),
			'type' => str_random(10),
			'subtype' => str_random(10),
		);
	}
}
