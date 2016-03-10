<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Core\Database\Seeds;

use Core\Bases\Database\Seeds\BaseSeeder;
use Core\Auth\Models\User;

/**
 * Seeder for the user_locations-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class UserRolesTableSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		$users = call_user_func_array(config('app.auth.models.user'). '::all', array());
		$roles = call_user_func_array(config('app.auth.models.role'). '::all', array());

		foreach ($users as $user)
		{
			$roles = $roles->shuffle();
			$count = rand(0, count($roles));

			for ($i=0; $i < $count; $i++)
			{
				$roleId = $roles[$i]->id;
				if (!$user->hasRole($roleId))
				{
					$user->roles()->attach($roleId);
				}
			}

		}
	}
}
