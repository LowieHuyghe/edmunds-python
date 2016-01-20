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

use Core\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder for the user_locations-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class UserRolesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		$users = User::all();
		$roles = \App\Models\Role::all();

		foreach ($users as $user)
		{
			shuffle($roles);
			$count = rand(0, count($roles));

			for ($i=0; $i < $count; $i++)
			{
				$user->roles()->attach($roles[$i]->id);
			}

		}
	}
}
