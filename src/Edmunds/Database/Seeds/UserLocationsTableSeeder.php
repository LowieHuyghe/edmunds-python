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
use Edmunds\Localization\Models\Location;
use Edmunds\Auth\Models\User;

/**
 * Seeder for the user_locations-table
 */
class UserLocationsTableSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		$users = call_user_func_array(config('app.auth.models.user'). '::all', array());

		foreach ($users as $user)
		{
			if (Location::where('user_id', '=', $user->id)->get()->count() == 0)
			{
				$location = Location::dummy();
				$location->user()->associate($user);
				$location->save();
			}
		}
	}
}
