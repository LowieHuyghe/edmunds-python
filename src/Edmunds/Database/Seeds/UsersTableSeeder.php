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
 * Seeder for the users-table
 */
class UsersTableSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		for ($i=0; $i < 100; $i++)
		{
			$user = call_user_func_array(config('app.auth.models.user'). '::dummy', array());

			if (call_user_func_array(config('app.auth.models.user'). '::where', array('email', '=', $user->email))->get()->count() == 0)
			{
				$user->save();
			}
		}
	}
}
