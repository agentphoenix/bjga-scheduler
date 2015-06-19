<?php

class StaffSeeder extends Seeder {

	public function run()
	{
		$faker = Faker\Factory::create();

		$access = [
			1 => 'staff',
			2 => 'associate',
		];

		for ($i = 0; $i < 5; $i++)
		{
			$user = UserModel::create([
				'name' => $faker->firstName." ".$faker->lastName,
				'email' => $faker->safeEmail,
				'password' => 'password',
				'phone' => '',
			]);

			$staff = StaffModel::create([
				'user_id' => $user->id,
				'title' => '',
				'access' => $faker->numberBetween(1, 2),
				'instruction' => (int) true,
			]);

			Artisan::call('scheduler:services', ['staff' => $staff->id, 'level' => $access[$staff->access]]);
		}
	}

}