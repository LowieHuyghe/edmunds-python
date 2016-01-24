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
 * @property string $token
 * @property User $user
 * @property string $session_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class AuthToken extends BaseModel
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
		//Set token if not set
		if (!$this->token)
		{
			$this->token = EncryptionHelper::encrypt(time() . '_' . $this->user->id);
		}

		return parent::save($options);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->required = array_merge($this->required, array('token', 'user_id', 'session_id'));

		$this->validator->value('token')->max(255);
		$this->validator->value('user_id')->integer();
		$this->validator->value('session_id')->max(255);
	}

}
