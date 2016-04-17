<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Auth\Models\Observers;

use Edmunds\Bases\Models\BaseModel;

/**
 * A base for the observers to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
