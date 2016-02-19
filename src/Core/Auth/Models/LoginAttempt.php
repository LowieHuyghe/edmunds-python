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

namespace Core\Auth\Models;
use Core\Bases\Models\BaseModel;
use Core\Auth\Models\User;
use Core\Validation\Validation;

/**
 * The model for files
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
		return $this->belongsTo(User::class);
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
		parent::addValidationRules();

		$this->required = array_merge($this->required, array('ip', 'type'));

		$validator->value('id')->integer();
		$validator->value('ip')->ip()->max(255);
		$validator->value('type')->max(255);

		$validator->value('email')->email()->max(255);
		$validator->value('pass')->max(255);
	}

}
