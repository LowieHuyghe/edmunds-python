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
use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PasswordReset extends BaseModel
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
	 * @param Validation $validator
	 */
	protected static function addValidationRules(&$validator)
	{
		$validator->value('email')->max(255)->email()->required();
		$validator->value('user_id')->integer()->required();
		$validator->value('token')->max(255)->required();

		$validator->value('created_at')->date();
		$validator->value('updated_at')->date();
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
			$latest = $passwordReset->created_at->addMinutes((int) env('CORE_AUTH_PASSWORDRESET_TTL'));
			$now = Carbon::now();

			if ($latest->gte($now))
			{
				$passwordReset->touch();
				return $passwordReset;
			}
		}

		return false;
	}

}
