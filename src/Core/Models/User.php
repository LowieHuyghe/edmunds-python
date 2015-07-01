<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Models;

use App\Models\Enums\RolesEnum;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LH\Core\Database\Relations\BelongsToManyEnums;
use LH\Core\Database\Relations\HasOneEnum;

/**
 * The model of the user
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property string $email Email of the user
 * @property string $password Password of the user
 * @property \stdClass[] $roles Roles for this user
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 */
class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Timestamps in the table
	 * @var bool|array
	 */
	public $timestamps = true;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->addValidationRules();
	}

	/**
	 * Roles belonging to this user
	 * @return BelongsToManyEnums
	 */
	public function roles()
	{
		return $this->belongsToManyEnums(RolesEnum::class, 'user_roles');
	}

	/**
	 * Check if user has role
	 * @param $roleId
	 * @return bool
	 */
	public function hasRole($roleId)
	{
		return $this->roles()->contains($roleId);
	}

	/**
	 * Add the validation of the model
	 */
	public function addValidationRules()
	{
		$this->validator->integer('id');

		$this->validator->required('email');
		$this->validator->max('email', 255);
		$this->validator->unique('email', 'users');

		$this->validator->max('password', 60);

		$this->validator->max('remember_token', 100);

		$this->validator->date('created_at');
		$this->validator->date('updated_at');
	}

}
