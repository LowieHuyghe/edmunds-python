<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Auth\Models;

use Core\Bases\Models\BaseModel;
use Core\Database\Relations\BelongsToEnum;
use Core\Database\Relations\BelongsToManyEnums;
use Core\Validation\Validator;
use Core\Localization\Format\DateTime;
use Core\Auth\Models\Gender;
use Core\Localization\Models\Localization;
use Core\Localization\Models\Location;
use Faker\Generator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The model of the user
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  *
 * @property int $id Database table-id
 * @property string $email Email of the user
 * @property string $password Password of the user
 * @property Collection $roles Roles for this user
 * @property Gender $gender Gender of the user
 * @property Localization $localization
 * @property Location $location
 * @property string $api_token
 * @property string $remember_token
 * @property DateTime created_at
 * @property DateTime updated_at
 * @property DateTime deleted_at
 */
class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * All the rights the user has
	 * @var Right[]
	 */
	private $rights;

	/**
	 * Roles belonging to this user
	 * @return BelongsToManyEnums
	 * @throws \Exception
	 */
	public function roles()
	{
		return $this->belongsToManyEnums(config('app.auth.models.role'), 'user_roles');
	}

	/**
	 * Gender of the user
	 * @return BelongsToEnum
	 */
	public function gender()
	{
		return $this->belongsToEnum(Gender::class);
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
	 * Check if user has right
	 * @param $rightId
	 * @return bool
	 */
	public function hasRight($rightId)
	{
		//Fetch all the rights
		if (!isset($this->rights))
		{
			$rights = array();
			$roles = $this->roles;
			$roles->each(function($role) use (&$rights)
			{
				$roleRights = $role->rights;
				$roleRights->each(function($right) use (&$rights)
				{
					$rights[] = $right->id;
				});
			});
			$this->rights = array_unique($rights);
		}

		return in_array($rightId, $this->rights);
	}

	/**
	 * Get the localization of the user
	 * @return HasOne
	 */
	public function localization()
	{
		return $this->hasOne(Localization::class);
	}

	/**
	 * Get the location of the user
	 * @return HasOne
	 */
	public function location()
	{
		return $this->hasOne(Location::class);
	}

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('email'));

		$validator->rule('id')->integer();
		$validator->rule('email')->max(255)->email();
		$validator->rule('gender_id')->integer();
		$validator->rule('password')->max(60);
		$validator->rule('api_token')->max(100);
		$validator->rule('remember_token')->max(100);

		$validator->rule('deleted_at')->date();
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'email' => $faker->email,
			'password' => bcrypt('secret'),
			'api_token' => str_random(32),
			'remember_token' => str_random(32),
			'gender_id' => Gender::all()->random()->id,
		);
	}

}