<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Database\Seeds;

use Edmunds\Bases\Database\Seeds\BaseSeeder;
use Edmunds\Auth\Models\User;

/**
 * Seeder for the user_locations-table
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
