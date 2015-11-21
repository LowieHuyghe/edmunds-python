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

namespace Core\Bases\Structures;

use Core\Bases\Models\BaseModel;
use DateTime;
use ArrayAccess;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Core\Io\Validation\Validation;

/**
 * A base for the structures to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
abstract class BaseStructure implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{

	/**
	 * The number of models to return for pagination.
	 *
	 * @var int
	 */
	protected $perPage = 15;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * The model's attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The attributes that should be visible in arrays.
	 *
	 * @var array
	 */
	protected $visible = [];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['*'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [];

	/**
	 * The storage format of the model's date columns.
	 *
	 * @var string
	 */
	protected $dateFormat;

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [];

	/**
	 * User exposed observable events.
	 *
	 * @var array
	 */
	protected $observables = [];

	/**
	 * Indicates if the model exists.
	 *
	 * @var bool
	 */
	public $exists = false;

	/**
	 * Indicates whether attributes are snake cased on arrays.
	 *
	 * @var bool
	 */
	public static $snakeAttributes = true;

	/**
	 * The event dispatcher instance.
	 *
	 * @var \Illuminate\Contracts\Events\Dispatcher
	 */
	protected static $dispatcher;

	/**
	 * The array of booted models.
	 *
	 * @var array
	 */
	protected static $booted = [];

	/**
	 * The array of global scopes on the model.
	 *
	 * @var array
	 */
	protected static $globalScopes = [];

	/**
	 * Indicates if all mass assignment is enabled.
	 *
	 * @var bool
	 */
	protected static $unguarded = false;

	/**
	 * The cache of the mutated attributes for each class.
	 *
	 * @var array
	 */
	protected static $mutatorCache = [];

	/**
	 * The name of the "created at" column.
	 *
	 * @var string
	 */
	const CREATED_AT = 'created_at';

	/**
	 * The name of the "updated at" column.
	 *
	 * @var string
	 */
	const UPDATED_AT = 'updated_at';

	/**
	 * The validator
	 * @var Validation
	 */
	protected $validator;

	/**
	 * Create a new Structure model instance.
	 *
	 * @param  array  $attributes
	 */
	public function __construct(array $attributes = [])
	{
		$this->bootIfNotBooted();

		$this->fill($attributes);

		$this->validator = new Validation();
		static::addValidationRules($this->validator, $this);
	}

	/**
	 * Check if the model needs to be booted and if so, do it.
	 *
	 * @return void
	 */
	protected function bootIfNotBooted()
	{
		$class = get_class($this);

		if (! isset(static::$booted[$class])) {
			static::$booted[$class] = true;

			$this->fireModelEvent('booting', false);

			static::boot();

			$this->fireModelEvent('booted', false);
		}
	}

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		static::bootTraits();
	}

	/**
	 * Boot all of the bootable traits on the model.
	 *
	 * @return void
	 */
	protected static function bootTraits()
	{
		foreach (class_uses_recursive(get_called_class()) as $trait) {
			if (method_exists(get_called_class(), $method = 'boot'.class_basename($trait))) {
				forward_static_call([get_called_class(), $method]);
			}
		}
	}

	/**
	 * Clear the list of booted models so they will be re-booted.
	 *
	 * @return void
	 */
	public static function clearBootedModels()
	{
		static::$booted = [];
	}

	/**
	 * Register an observer with the Model.
	 *
	 * @param  object|string  $class
	 * @param  int  $priority
	 * @return void
	 */
	public static function observe($class, $priority = 0)
	{
		$instance = new static;

		$className = is_string($class) ? $class : get_class($class);

		// When registering a model observer, we will spin through the possible events
		// and determine if this observer has that method. If it does, we will hook
		// it into the model's event system, making it convenient to watch these.
		foreach ($instance->getObservableEvents() as $event) {
			if (method_exists($class, $event)) {
				static::registerModelEvent($event, $className.'@'.$event, $priority);
			}
		}
	}

	/**
	 * Fill the model with an array of attributes.
	 *
	 * @param  array  $attributes
	 * @return $this
	 *
	 * @throws \Illuminate\Database\Eloquent\MassAssignmentException
	 */
	public function fill(array $attributes)
	{
		$totallyGuarded = $this->totallyGuarded();

		foreach ($this->fillableFromArray($attributes) as $key => $value) {
			$key = $this->removeTableFromKey($key);

			// The developers may choose to place some attributes in the "fillable"
			// array, which means only those attributes may be set through mass
			// assignment to the model, and all others will just be ignored.
			if ($this->isFillable($key)) {
				$this->setAttribute($key, $value);
			} elseif ($totallyGuarded) {
				throw new MassAssignmentException($key);
			}
		}

		return $this;
	}

	/**
	 * Fill the model with an array of attributes. Force mass assignment.
	 *
	 * @param  array  $attributes
	 * @return $this
	 */
	public function forceFill(array $attributes)
	{
		// Since some versions of PHP have a bug that prevents it from properly
		// binding the late static context in a closure, we will first store
		// the model in a variable, which we will then use in the closure.
		$model = $this;

		return static::unguarded(function () use ($model, $attributes) {
			return $model->fill($attributes);
		});
	}

	/**
	 * Get the fillable attributes of a given array.
	 *
	 * @param  array  $attributes
	 * @return array
	 */
	protected function fillableFromArray(array $attributes)
	{
		if (count($this->fillable) > 0 && ! static::$unguarded) {
			return array_intersect_key($attributes, array_flip($this->fillable));
		}

		return $attributes;
	}

	/**
	 * Create a new instance of the given model.
	 *
	 * @param  array  $attributes
	 * @param  bool   $exists
	 * @return static
	 */
	public function newInstance($attributes = [], $exists = false)
	{
		// This method just provides a convenient way for us to generate fresh model
		// instances of this current model.
		$model = new static((array) $attributes);

		$model->exists = $exists;

		return $model;
	}

	/**
	 * Append attributes to query when building a query.
	 *
	 * @param  array|string  $attributes
	 * @return $this
	 */
	public function append($attributes)
	{
		if (is_string($attributes)) {
			$attributes = func_get_args();
		}

		$this->appends = array_unique(
			array_merge($this->appends, $attributes)
		);

		return $this;
	}

	/**
	 * Remove all of the event listeners for the model.
	 *
	 * @return void
	 */
	public static function flushEventListeners()
	{
		if (! isset(static::$dispatcher)) {
			return;
		}

		$instance = new static;

		foreach ($instance->getObservableEvents() as $event) {
			static::$dispatcher->forget("structure.{$event}: ".get_called_class());
		}
	}

	/**
	 * Register a model event with the dispatcher.
	 *
	 * @param  string  $event
	 * @param  \Closure|string  $callback
	 * @param  int  $priority
	 * @return void
	 */
	protected static function registerModelEvent($event, $callback, $priority = 0)
	{
		if (isset(static::$dispatcher)) {
			$name = get_called_class();

			static::$dispatcher->listen("structure.{$event}: {$name}", $callback, $priority);
		}
	}

	/**
	 * Get the observable event names.
	 *
	 * @return array
	 */
	public function getObservableEvents()
	{
		return $this->observables;
	}

	/**
	 * Set the observable event names.
	 *
	 * @param  array  $observables
	 * @return void
	 */
	public function setObservableEvents(array $observables)
	{
		$this->observables = $observables;
	}

	/**
	 * Add an observable event name.
	 *
	 * @param  mixed  $observables
	 * @return void
	 */
	public function addObservableEvents($observables)
	{
		$observables = is_array($observables) ? $observables : func_get_args();

		$this->observables = array_unique(array_merge($this->observables, $observables));
	}

	/**
	 * Remove an observable event name.
	 *
	 * @param  mixed  $observables
	 * @return void
	 */
	public function removeObservableEvents($observables)
	{
		$observables = is_array($observables) ? $observables : func_get_args();

		$this->observables = array_diff($this->observables, $observables);
	}

	/**
	 * Fire the given event for the model.
	 *
	 * @param  string  $event
	 * @param  bool    $halt
	 * @return mixed
	 */
	protected function fireModelEvent($event, $halt = true)
	{
		if (! isset(static::$dispatcher)) {
			return true;
		}

		// We will append the names of the class to the event to distinguish it from
		// other model events that are fired, allowing us to listen on each model
		// event set individually instead of catching event for all the models.
		$event = "structure.{$event}: ".get_class($this);

		$method = $halt ? 'until' : 'fire';

		return static::$dispatcher->$method($event, $this);
	}

	/**
	 * Update the creation and update timestamps.
	 *
	 * @return void
	 */
	protected function updateTimestamps()
	{
		$time = $this->freshTimestamp();

		if (! $this->isDirty(static::UPDATED_AT)) {
			$this->setUpdatedAt($time);
		}

		if (! $this->exists && ! $this->isDirty(static::CREATED_AT)) {
			$this->setCreatedAt($time);
		}
	}

	/**
	 * Set the value of the "created at" attribute.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function setCreatedAt($value)
	{
		$this->{static::CREATED_AT} = $value;
	}

	/**
	 * Set the value of the "updated at" attribute.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function setUpdatedAt($value)
	{
		$this->{static::UPDATED_AT} = $value;
	}

	/**
	 * Get the name of the "created at" column.
	 *
	 * @return string
	 */
	public function getCreatedAtColumn()
	{
		return static::CREATED_AT;
	}

	/**
	 * Get the name of the "updated at" column.
	 *
	 * @return string
	 */
	public function getUpdatedAtColumn()
	{
		return static::UPDATED_AT;
	}

	/**
	 * Get a fresh timestamp for the model.
	 *
	 * @return \Carbon\Carbon
	 */
	public function freshTimestamp()
	{
		return new Carbon;
	}

	/**
	 * Get a fresh timestamp for the model.
	 *
	 * @return string
	 */
	public function freshTimestampString()
	{
		return $this->fromDateTime($this->freshTimestamp());
	}

	/**
	 * Create a new Collection instance.
	 *
	 * @param  array  $models
	 * @return Collection
	 */
	public function newCollection(array $models = [])
	{
		return collect($models);
	}

	/**
	 * Determine if the model uses timestamps.
	 *
	 * @return bool
	 */
	public function usesTimestamps()
	{
		return $this->timestamps;
	}

	/**
	 * Get the number of models to return per page.
	 *
	 * @return int
	 */
	public function getPerPage()
	{
		return $this->perPage;
	}

	/**
	 * Set the number of models to return per page.
	 *
	 * @param  int   $perPage
	 * @return void
	 */
	public function setPerPage($perPage)
	{
		$this->perPage = $perPage;
	}

	/**
	 * Get the hidden attributes for the model.
	 *
	 * @return array
	 */
	public function getHidden()
	{
		return $this->hidden;
	}

	/**
	 * Set the hidden attributes for the model.
	 *
	 * @param  array  $hidden
	 * @return void
	 */
	public function setHidden(array $hidden)
	{
		$this->hidden = $hidden;
	}

	/**
	 * Add hidden attributes for the model.
	 *
	 * @param  array|string|null  $attributes
	 * @return void
	 */
	public function addHidden($attributes = null)
	{
		$attributes = is_array($attributes) ? $attributes : func_get_args();

		$this->hidden = array_merge($this->hidden, $attributes);
	}

	/**
	 * Make the given, typically hidden, attributes visible.
	 *
	 * @param  array|string  $attributes
	 * @return $this
	 */
	public function withHidden($attributes)
	{
		$this->hidden = array_diff($this->hidden, (array) $attributes);

		return $this;
	}

	/**
	 * Get the visible attributes for the model.
	 *
	 * @return array
	 */
	public function getVisible()
	{
		return $this->visible;
	}

	/**
	 * Set the visible attributes for the model.
	 *
	 * @param  array  $visible
	 * @return void
	 */
	public function setVisible(array $visible)
	{
		$this->visible = $visible;
	}

	/**
	 * Add visible attributes for the model.
	 *
	 * @param  array|string|null  $attributes
	 * @return void
	 */
	public function addVisible($attributes = null)
	{
		$attributes = is_array($attributes) ? $attributes : func_get_args();

		$this->visible = array_merge($this->visible, $attributes);
	}

	/**
	 * Set the accessors to append to model arrays.
	 *
	 * @param  array  $appends
	 * @return void
	 */
	public function setAppends(array $appends)
	{
		$this->appends = $appends;
	}

	/**
	 * Get the fillable attributes for the model.
	 *
	 * @return array
	 */
	public function getFillable()
	{
		return $this->fillable;
	}

	/**
	 * Set the fillable attributes for the model.
	 *
	 * @param  array  $fillable
	 * @return $this
	 */
	public function fillable(array $fillable)
	{
		$this->fillable = $fillable;

		return $this;
	}

	/**
	 * Get the guarded attributes for the model.
	 *
	 * @return array
	 */
	public function getGuarded()
	{
		return $this->guarded;
	}

	/**
	 * Set the guarded attributes for the model.
	 *
	 * @param  array  $guarded
	 * @return $this
	 */
	public function guard(array $guarded)
	{
		$this->guarded = $guarded;

		return $this;
	}

	/**
	 * Disable all mass assignable restrictions.
	 *
	 * @param  bool  $state
	 * @return void
	 */
	public static function unguard($state = true)
	{
		static::$unguarded = $state;
	}

	/**
	 * Enable the mass assignment restrictions.
	 *
	 * @return void
	 */
	public static function reguard()
	{
		static::$unguarded = false;
	}

	/**
	 * Determine if current state is "unguarded".
	 *
	 * @return bool
	 */
	public static function isUnguarded()
	{
		return static::$unguarded;
	}

	/**
	 * Run the given callable while being unguarded.
	 *
	 * @param  callable  $callback
	 * @return mixed
	 */
	public static function unguarded(callable $callback)
	{
		if (static::$unguarded) {
			return $callback();
		}

		static::unguard();

		$result = $callback();

		static::reguard();

		return $result;
	}

	/**
	 * Determine if the given attribute may be mass assigned.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function isFillable($key)
	{
		if (static::$unguarded) {
			return true;
		}

		// If the key is in the "fillable" array, we can of course assume that it's
		// a fillable attribute. Otherwise, we will check the guarded array when
		// we need to determine if the attribute is black-listed on the model.
		if (in_array($key, $this->fillable)) {
			return true;
		}

		if ($this->isGuarded($key)) {
			return false;
		}

		return empty($this->fillable) && ! Str::startsWith($key, '_');
	}

	/**
	 * Determine if the given key is guarded.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function isGuarded($key)
	{
		return in_array($key, $this->guarded) || $this->guarded == ['*'];
	}

	/**
	 * Determine if the model is totally guarded.
	 *
	 * @return bool
	 */
	public function totallyGuarded()
	{
		return count($this->fillable) == 0 && $this->guarded == ['*'];
	}

	/**
	 * Remove the table name from a given key.
	 *
	 * @param  string  $key
	 * @return string
	 */
	protected function removeTableFromKey($key)
	{
		if (! Str::contains($key, '.')) {
			return $key;
		}

		return last(explode('.', $key));
	}

	/**
	 * Convert the model instance to JSON.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->jsonSerialize(), $options);
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * Convert the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributesToArray();
	}

	/**
	 * Convert the model's attributes to an array.
	 *
	 * @return array
	 */
	public function attributesToArray()
	{
		$attributes = $this->getArrayableAttributes();

		// If an attribute is a date, we will cast it to a string after converting it
		// to a DateTime / Carbon instance. This is so we will get some consistent
		// formatting while accessing attributes vs. arraying / JSONing a model.
		foreach ($this->getDates() as $key) {
			if (! isset($attributes[$key])) {
				continue;
			}

			$attributes[$key] = $this->serializeDate(
				$this->asDateTime($attributes[$key])
			);
		}

		$mutatedAttributes = $this->getMutatedAttributes();

		// We want to spin through all the mutated attributes for this model and call
		// the mutator for the attribute. We cache off every mutated attributes so
		// we don't have to constantly check on attributes that actually change.
		foreach ($mutatedAttributes as $key) {
			if (! array_key_exists($key, $attributes)) {
				continue;
			}

			$attributes[$key] = $this->mutateAttributeForArray(
				$key, $attributes[$key]
			);
		}

		// Next we will handle any casts that have been setup for this model and cast
		// the values to their appropriate type. If the attribute has a mutator we
		// will not perform the cast on those attributes to avoid any confusion.
		foreach ($this->casts as $key => $value) {
			if (! array_key_exists($key, $attributes) ||
				in_array($key, $mutatedAttributes)) {
				continue;
			}

			$attributes[$key] = $this->castAttribute(
				$key, $attributes[$key]
			);
		}

		// Here we will grab all of the appended, calculated attributes to this model
		// as these attributes are not really in the attributes array, but are run
		// when we need to array or JSON the model for convenience to the coder.
		foreach ($this->getArrayableAppends() as $key) {
			$attributes[$key] = $this->mutateAttributeForArray($key, null);
		}

		return $attributes;
	}

	/**
	 * Get an attribute array of all arrayable attributes.
	 *
	 * @return array
	 */
	protected function getArrayableAttributes()
	{
		return $this->getArrayableItems($this->attributes);
	}

	/**
	 * Get all of the appendable values that are arrayable.
	 *
	 * @return array
	 */
	protected function getArrayableAppends()
	{
		if (! count($this->appends)) {
			return [];
		}

		return $this->getArrayableItems(
			array_combine($this->appends, $this->appends)
		);
	}


	/**
	 * Get an attribute array of all arrayable values.
	 *
	 * @param  array  $values
	 * @return array
	 */
	protected function getArrayableItems(array $values)
	{
		if (count($this->getVisible()) > 0) {
			return array_intersect_key($values, array_flip($this->getVisible()));
		}

		return array_diff_key($values, array_flip($this->getHidden()));
	}

	/**
	 * Get an attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		return $this->getAttributeValue($key);
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttributeValue($key)
	{
		$value = $this->getAttributeFromArray($key);

		// If the attribute has a get mutator, we will call that then return what
		// it returns as the value, which is useful for transforming values on
		// retrieval from the model to a form that is more useful for usage.
		if ($this->hasGetMutator($key)) {
			return $this->mutateAttribute($key, $value);
		}

		// If the attribute exists within the cast array, we will convert it to
		// an appropriate native PHP type dependant upon the associated value
		// given with the key in the pair. Dayle made this comment line up.
		if ($this->hasCast($key)) {
			$value = $this->castAttribute($key, $value);
		}

		// If the attribute is listed as a date, we will convert it to a DateTime
		// instance on retrieval, which makes it quite convenient to work with
		// date fields without having to create a mutator for each property.
		elseif (in_array($key, $this->getDates())) {
			if (! is_null($value)) {
				return $this->asDateTime($value);
			}
		}

		return $value;
	}

	/**
	 * Get an attribute from the $attributes array.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	protected function getAttributeFromArray($key)
	{
		if (array_key_exists($key, $this->attributes)) {
			return $this->attributes[$key];
		}
	}

	/**
	 * Determine if a get mutator exists for an attribute.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function hasGetMutator($key)
	{
		return method_exists($this, 'get'.Str::studly($key).'Attribute');
	}

	/**
	 * Get the value of an attribute using its mutator.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	protected function mutateAttribute($key, $value)
	{
		return $this->{'get'.Str::studly($key).'Attribute'}($value);
	}

	/**
	 * Get the value of an attribute using its mutator for array conversion.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	protected function mutateAttributeForArray($key, $value)
	{
		$value = $this->mutateAttribute($key, $value);

		return $value instanceof Arrayable ? $value->toArray() : $value;
	}

	/**
	 * Determine whether an attribute should be casted to a native type.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	protected function hasCast($key)
	{
		return array_key_exists($key, $this->casts);
	}

	/**
	 * Determine whether a value is JSON castable for inbound manipulation.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	protected function isJsonCastable($key)
	{
		if ($this->hasCast($key)) {
			return in_array(
				$this->getCastType($key), ['array', 'json', 'object', 'collection'], true
			);
		}

		return false;
	}

	/**
	 * Get the type of cast for a model attribute.
	 *
	 * @param  string  $key
	 * @return string
	 */
	protected function getCastType($key)
	{
		return trim(strtolower($this->casts[$key]));
	}

	/**
	 * Cast an attribute to a native PHP type.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	protected function castAttribute($key, $value)
	{
		if (is_null($value)) {
			return $value;
		}

		switch ($this->getCastType($key)) {
			case 'int':
			case 'integer':
				return (int) $value;
			case 'real':
			case 'float':
			case 'double':
				return (float) $value;
			case 'string':
				return (string) $value;
			case 'bool':
			case 'boolean':
				return (bool) $value;
			case 'object':
				return json_decode($value);
			case 'array':
			case 'json':
				return json_decode($value, true);
			case 'collection':
				return collect(json_decode($value, true));
			default:
				return $value;
		}
	}

	/**
	 * Set a given attribute on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function setAttribute($key, $value)
	{
		// First we will check for the presence of a mutator for the set operation
		// which simply lets the developers tweak the attribute as it is set on
		// the model, such as "json_encoding" an listing of data for storage.
		if ($this->hasSetMutator($key)) {
			$method = 'set'.Str::studly($key).'Attribute';

			return $this->{$method}($value);
		}

		// If an attribute is listed as a "date", we'll convert it from a DateTime
		// instance into a form proper for storage on the database tables using
		// the connection grammar's date format. We will auto set the values.
		elseif (in_array($key, $this->getDates()) && $value) {
			$value = $this->fromDateTime($value);
		}

		if ($this->isJsonCastable($key) && ! is_null($value)) {
			$value = json_encode($value);
		}

		$this->attributes[$key] = $value;
	}

	/**
	 * Determine if a set mutator exists for an attribute.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function hasSetMutator($key)
	{
		return method_exists($this, 'set'.Str::studly($key).'Attribute');
	}

	/**
	 * Get the attributes that should be converted to dates.
	 *
	 * @return array
	 */
	public function getDates()
	{
		$defaults = [static::CREATED_AT, static::UPDATED_AT];

		return $this->timestamps ? array_merge($this->dates, $defaults) : $this->dates;
	}

	/**
	 * Convert a DateTime to a storable string.
	 *
	 * @param  \DateTime|int  $value
	 * @return string
	 */
	public function fromDateTime($value)
	{
		$format = $this->getDateFormat();

		$value = $this->asDateTime($value);

		return $value->format($format);
	}

	/**
	 * Return a timestamp as DateTime object.
	 *
	 * @param  mixed  $value
	 * @return \Carbon\Carbon
	 */
	protected function asDateTime($value)
	{
		// If this value is already a Carbon instance, we shall just return it as is.
		// This prevents us having to reinstantiate a Carbon instance when we know
		// it already is one, which wouldn't be fulfilled by the DateTime check.
		if ($value instanceof Carbon) {
			return $value;
		}

		// If the value is already a DateTime instance, we will just skip the rest of
		// these checks since they will be a waste of time, and hinder performance
		// when checking the field. We will just return the DateTime right away.
		if ($value instanceof DateTime) {
			return Carbon::instance($value);
		}

		// If this value is an integer, we will assume it is a UNIX timestamp's value
		// and format a Carbon object from this timestamp. This allows flexibility
		// when defining your date fields as they might be UNIX timestamps here.
		if (is_numeric($value)) {
			return Carbon::createFromTimestamp($value);
		}

		// If the value is in simply year, month, day format, we will instantiate the
		// Carbon instances from that format. Again, this provides for simple date
		// fields on the database, while still supporting Carbonized conversion.
		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
			return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
		}

		// Finally, we will just assume this date is in the format used by default on
		// the database connection and use that format to create the Carbon object
		// that is returned back out to the developers after we convert it here.
		return Carbon::createFromFormat($this->getDateFormat(), $value);
	}

	/**
	 * Prepare a date for array / JSON serialization.
	 *
	 * @param  \DateTime  $date
	 * @return string
	 */
	protected function serializeDate(DateTime $date)
	{
		return $date->format($this->getDateFormat());
	}

	/**
	 * Get the format for database stored dates.
	 *
	 * @return string
	 */
	protected function getDateFormat()
	{
		return $this->dateFormat ?: 'Y-m-d H:i:s';
	}

	/**
	 * Set the date format used by the model.
	 *
	 * @param  string  $format
	 * @return $this
	 */
	public function setDateFormat($format)
	{
		$this->dateFormat = $format;

		return $this;
	}

	/**
	 * Get all of the current attributes on the model.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Set the array of model attributes. No checking is done.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function setRawAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * Determine if the model or given attribute(s) have been modified.
	 *
	 * @param  array|string|null  $attributes
	 * @return bool
	 */
	public function isDirty($attributes = null)
	{
		$dirty = $this->getDirty();

		if (is_null($attributes)) {
			return count($dirty) > 0;
		}

		if (! is_array($attributes)) {
			$attributes = func_get_args();
		}

		foreach ($attributes as $attribute) {
			if (array_key_exists($attribute, $dirty)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the attributes that have been changed since last sync.
	 *
	 * @return array
	 */
	public function getDirty()
	{
		$dirty = [];

		foreach ($this->attributes as $key => $value) {
			$dirty[$key] = $value;
		}

		return $dirty;
	}

	/**
	 * Get the event dispatcher instance.
	 *
	 * @return \Illuminate\Contracts\Events\Dispatcher
	 */
	public static function getEventDispatcher()
	{
		return static::$dispatcher;
	}

	/**
	 * Set the event dispatcher instance.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
	 * @return void
	 */
	public static function setEventDispatcher(Dispatcher $dispatcher)
	{
		static::$dispatcher = $dispatcher;
	}

	/**
	 * Unset the event dispatcher for models.
	 *
	 * @return void
	 */
	public static function unsetEventDispatcher()
	{
		static::$dispatcher = null;
	}

	/**
	 * Get the mutated attributes for a given instance.
	 *
	 * @return array
	 */
	public function getMutatedAttributes()
	{
		$class = get_class($this);

		if (! isset(static::$mutatorCache[$class])) {
			static::cacheMutatedAttributes($class);
		}

		return static::$mutatorCache[$class];
	}

	/**
	 * Extract and cache all the mutated attributes of a class.
	 *
	 * @param string $class
	 * @return void
	 */
	public static function cacheMutatedAttributes($class)
	{
		$mutatedAttributes = [];

		// Here we will extract all of the mutated attributes so that we can quickly
		// spin through them after we export models to their array form, which we
		// need to be fast. This'll let us know the attributes that can mutate.
		foreach (get_class_methods($class) as $method) {
			if (strpos($method, 'Attribute') !== false &&
				preg_match('/^get(.+)Attribute$/', $method, $matches)) {
				if (static::$snakeAttributes) {
					$matches[1] = Str::snake($matches[1]);
				}

				$mutatedAttributes[] = lcfirst($matches[1]);
			}
		}

		static::$mutatorCache[$class] = $mutatedAttributes;
	}

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * Dynamically set attributes on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->setAttribute($key, $value);
	}

	/**
	 * Determine if the given attribute exists.
	 *
	 * @param  mixed  $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}

	/**
	 * Get the value for a given offset.
	 *
	 * @param  mixed  $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	/**
	 * Set the value for a given offset.
	 *
	 * @param  mixed  $offset
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	/**
	 * Unset the value for a given offset.
	 *
	 * @param  mixed  $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}

	/**
	 * Determine if an attribute exists on the model.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->attributes[$key]) ||
		($this->hasGetMutator($key) && ! is_null($this->getAttributeValue($key)));
	}

	/**
	 * Unset an attribute on the model.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __unset($key)
	{
		unset($this->attributes[$key]);
	}

	/**
	 * Handle dynamic static method calls into the method.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$instance = new static;

		return call_user_func_array([$instance, $method], $parameters);
	}

	/**
	 * Convert the model to its string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * When a model is being unserialized, check if it needs to be booted.
	 *
	 * @return void
	 */
	public function __wakeup()
	{
		$this->bootIfNotBooted();
	}

	/**
	 * Check if model has errors
	 * @return bool
	 */
	public function hasErrors()
	{
		$this->validator->setInput($this->getAttributes());

		return $this->validator->hasErrors();
	}

	/**
	 * Return the validator with the errors
	 * @return Validator
	 */
	public function getErrors()
	{
		$this->validator->setInput($this->getAttributes());

		return $this->validator->getErrors();
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 * @param BaseModel $model
	 */
	protected static function addValidationRules(&$validator, $model)
	{
		//
	}

	/**
	 * Create an instance of the model
	 * @param array $attributes
	 * @return BaseModel
	 */
	public static function dummy($attributes = array())
	{
		$className = get_called_class();

		$factory = app('Illuminate\Database\Eloquent\Factory');
		$factory->define($className, array($className, 'factory'));

		return factory($className)->make($attributes);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 * @throws \Exception
	 */
	protected static function factory($faker)
	{
		throw new \Exception('Factory not defined in ' . get_called_class());
	}
}
