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
use Core\Localization\DateTime;
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
 * @property string $email
 * @property User $user
 * @property string $token
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class PasswordReset extends BaseModel
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
	 * Save the password-reset
	 * @param array $options
	 * @return bool
	 */
	public function save(array $options = [])
	{
		//Set token
		$this->token = EncryptionHelper::encrypt(time() . '_' . $this->email);

		//Set user, if one
		$user = User::where('email', '=', $this->email)->first();
		if ($user)
		{
			$this->user()->associate($user);
		}

		return parent::save($options);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->validator->value('email')->max(255)->email()->required();
		$this->validator->value('user_id')->integer()->required();
		$this->validator->value('token')->max(255)->required();
	}

	/**
	 * Check if password-reset is still valid, and return it
	 * @param string $token
	 * @return PasswordReset
	 */
	public static function getWithValidCheck($token)
	{
		$passwordReset = PasswordReset::where('token', '=', $token)->first();

		if ($passwordReset)
		{
			$latest = $passwordReset->created_at->addMinutes(config('core.auth.ttl.passwordreset'));
			$now = DateTime::now();

			if ($latest->gte($now))
			{
				$passwordReset->touch();
				return $passwordReset;
			}
		}

		return false;
	}

}
