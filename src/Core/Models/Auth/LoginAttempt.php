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

namespace Core\Models\Auth;
use Core\Helpers\EncryptionHelper;
use Core\Bases\Models\BaseModel;
use Core\Models\User;
use Core\Io\Validation\Validation;

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
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class LoginAttempt extends BaseModel
{
	/**
	 * Enable or disable timestamps by default
	 * @var boolean
	 */
	public $timestamps = true;

	/**
	 * The attributes that should be mutated to dates.
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'];

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
			return EncryptionHelper::decrypt($this->pass);
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
			$this->pass = EncryptionHelper::encrypt($password);
		}
		else
		{
			$this->pass = null;
		}
	}

	/**
	 * Add the validation of the model
	 * @param Validation $validator
	 * @param BaseModel $model
	 */
	protected static function addValidationRules(&$validator, $model)
	{
		$validator->value('id')->integer()->required();
		$validator->value('ip')->ip()->max(255)->required();
		$validator->value('type')->max(255)->required();

		$validator->value('email')->email()->max(255);
		$validator->value('pass')->max(255);

		$validator->value('created_at')->date();
		$validator->value('updated_at')->date();
	}

}
