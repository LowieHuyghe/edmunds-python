<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Core\Database\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Relation for has OneEnum
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class HasOneEnum extends HasOne
{
	/**
	 * The enumClass used to get the name for the id
	 * @var string
	 */
	protected $enumClass;

	/**
	 * Create a new has one or many relationship instance.
	 *
	 * @param  Builder  $query
	 * @param  Model  $parent
	 * @param  string  $enumClass
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 */
	public function __construct(Builder $query, Model $parent, $enumClass, $foreignKey, $localKey)
	{
		$this->enumClass = $enumClass;

		if (is_null($foreignKey))
		{
			$foreignKey = snake_case(str_singular(last(explode('\\', $enumClass))));
		}

		parent::__construct($query, $parent, $foreignKey, $localKey);
	}

	public function getResults()
	{
		$id = $this->parent->{$this->foreignKey};
		if ($id)
		{
			$enumClass = $this->enumClass;
			return $enumClass::find($id);
		}

		return null;
	}

	/**
	 * @deprecated
	 */
	public function first() {}
	/**
	 * @deprecated
	 */
	public function findOrNew($id, $columns = ['*']) {}
	/**
	 * @deprecated
	 */
	public function firstOrNew(array $attributes) {}
	/**
	 * @deprecated
	 */
	public function firstOrCreate(array $attributes) {}
}
