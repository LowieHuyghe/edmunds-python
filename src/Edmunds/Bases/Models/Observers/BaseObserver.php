<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Models\Observers;

use Edmunds\Bases\Models\BaseModel;

/**
 * A base for the observers to extend from
 */
class BaseObserver
{

	/**
	 * Constructor which initiates the validator
	 */
	public function __construct()
	{

	}

	/**
	 * Event called when the model is being created
	 * @param BaseModel $model Model that is being created
	 * @return bool
	 */
	public function creating($model)
	{
		return $model->hasErrors();
	}

	/**
	 * Event called when the model is created
	 * @param BaseModel $model Model that is created
	 */
	public function created($model)
	{

	}

	/**
	 * Event called when the model is being updated
	 * @param BaseModel $model Model that is being updated
	 * @return bool
	 */
	public function updating($model)
	{
		return $model->hasErrors();
	}

	/**
	 * Event called when the model is updated
	 * @param BaseModel $model Model that is updated
	 */
	public function updated($model)
	{

	}

	/**
	 * Event called when the model is being saved
	 * @param BaseModel $model Model that is being saved
	 */
	public function saving($model)
	{

	}

	/**
	 * Event called when the model is saved
	 * @param BaseModel $model Model that is saved
	 */
	public function saved($model)
	{

	}

	/**
	 * Event called when the model is being deleted
	 * @param BaseModel $model Model that is being deleted
	 */
	public function deleting($model)
	{

	}

	/**
	 * Event called when the model is deleted
	 * @param BaseModel $model Model that is deleted
	 */
	public function deleted($model)
	{

	}

	/**
	 * Event called when the model is being restored
	 * @param BaseModel $model Model that is being restored
	 */
	public function restoring($model)
	{

	}

	/**
	 * Event called when the model is restored
	 * @param BaseModel $model Model that is restored
	 */
	public function restored($model)
	{

	}

	/**
	 * Set conditional rules for the validation
	 * @param BaseModel $model Model that is restored
	 */
	protected function setConditionalRules($model)
	{

	}

}
