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

use Core\Models\Gender;
use Core\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder for the users-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		$password = app('hash')->make('secret');

		for ($i=0; $i < 100; $i++)
		{
			$user = User::dummy();
			if ($i == 0)
			{
				$user->email = 'iam@lowiehuyghe';
				$user->gender_id = Gender::MALE;
			}
			$user->password = $password;
			$user->save();
		}
	}
}
