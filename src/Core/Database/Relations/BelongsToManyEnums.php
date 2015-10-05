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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Core\Models\Enum;
use Core\Models\Enums\BaseEnum;

/**
 * Relation for has ManyEnums
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class BelongsToManyEnums extends BelongsToMany
{
	/**
	 * The enumClass used to get the name for the id
	 * @var string
	 */
	protected $enumClass;

	/**
	 * Create a new belongs to many relationship instance.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \Illuminate\Database\Eloquent\Model  $parent
	 * @param  string  $enumClass
	 * @param  string  $table
	 * @param  string  $foreignKey
	 * @param  string  $otherKey
	 * @param  string  $relationName
	 */
	public function __construct(Builder $query, Model $parent, $enumClass, $table, $foreignKey, $otherKey, $relationName = null)
	{
		// If no table name was provided, we can guess it by concatenating the two
		// models using underscores in alphabetical order. The two model names
		// are transformed to snake case from their default CamelCase also.
		if (is_null($table)) {
			$enumClassSplit = str_plural(last(explode('\\', $enumClass)));
			$table = $this->joiningTable($enumClassSplit);
		}

		if (is_null($otherKey))
		{
			$otherKey = snake_case(str_singular(last(explode('\\', $enumClass)))) . '_id';
		}

		$this->enumClass = $enumClass;
		$query->getQuery()->from = $table;

		parent::__construct($query, $parent, $table, $foreignKey, $otherKey, $relationName);
	}

	/**
	 * Set the join clause for the relation query.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder|null  $query
	 * @return $this
	 */
	protected function setJoin($query = null)
	{
		return $this;
	}

	/**
	 * Set the select clause for the relation query.
	 *
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	protected function getSelectColumns(array $columns = ['*'])
	{
		$defaults = [$this->otherKey];

		// We need to alias all of the pivot columns with the "pivot_" prefix so we
		// can easily extract them out of the models and put them into the pivot
		// relationships when they are retrieved and hydrated into the models.
		$columns = [];

		foreach (array_merge($defaults, $this->pivotColumns) as $column) {
			$columns[] = $this->table.'.'.$column.' as id';
		}

		return array_merge($columns);
	}

	/**
	 * Execute the query as a "select" statement.
	 *
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function get($columns = ['*'])
	{
		$collection = parent::get($columns);

		$enumClass = $this->enumClass;

		$collection = collect($collection)->map(function($item) use($enumClass)
		{
			return $enumClass::find($item->id);
		});

		return $collection;
	}
}
