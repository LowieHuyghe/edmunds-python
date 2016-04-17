<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Models;

use Edmunds\Bases\Models\BaseModel;
use Edmunds\Auth\Models\User;
use Edmunds\Validation\Validator;

/**
 * The model for files
 *
 * @property int $id
 * @property string $ip
 * @property string $type
 * @property string $email
 * @property string $password
 * @property User $user
 * @property DateTime created_at
 * @property DateTime updated_at
 */
class LoginAttempt extends BaseModel
{
	/**
	 * Get the user
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(config('app.auth.models.user'));
	}

	/**
	 * Get the password of the attempt
	 * @return string
	 */
	protected function getPasswordAttribute()
	{
		if ($this->pass)
		{
			return decrypt($this->pass);
		}
		return null;
	}

	/**
	 * Set the password of the attempt
	 * @param string $password
	 */
	protected function setPasswordAttribute($password)
	{
		if ($password)
		{
			$this->pass = encrypt($password);
		}
		else
		{
			$this->pass = null;
		}
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('ip', 'type'));

		$validator->rule('id')->integer();
		$validator->rule('ip')->ip()->max(255);
		$validator->rule('type')->max(255);

		$validator->rule('email')->email()->max(255);
		$validator->rule('pass')->max(255);
	}

}
