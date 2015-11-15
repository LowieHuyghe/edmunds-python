<?php

namespace Core\Database\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Expression;

class BelongsToEnum extends BelongsTo
{
	/**
	 * The enumClass used to get the name for the id
	 * @var string
	 */
	protected $enumClass;

	/**
	* Create a new belongs to relationship instance.
	*
	* @param  \Illuminate\Database\Eloquent\Builder  $query
	* @param  \Illuminate\Database\Eloquent\Model  $parent
	* @param  string  $foreignKey
	* @param  string  $otherKey
	* @param  string  $relation
	* @return void
	*/
	public function __construct(Builder $query, Model $parent, $enumClass, $foreignKey, $otherKey, $relation)
	{
		$this->enumClass = $enumClass;

		parent::__construct($query, $parent, $foreignKey, $otherKey, $relation);
	}

	/**
	 * Get the results of the relationship.
	 *
	 * @return mixed
	 */
	public function getResults()
	{
		$enumClass = $this->enumClass;
		$foreignKey = $this->foreignKey;

		return $enumClass::find($this->parent->$foreignKey);
	}

}
