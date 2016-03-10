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
 * Seeder for the users-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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

			if (call_user_func_array(config('app.auth.models.user'). '::where', array('user_id', '=', $user->id))->get()->count() == 0)
			{
				$user->save();
			}
		}
	}
}
