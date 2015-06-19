<?php

class UserSeeder extends Seeder {

	public function run()
	{
		$faker = Faker\Factory::create();

		for ($i = 0; $i < 50; $i++)
		{
			UserModel::create([
				'name' => $faker->firstName." ".$faker->lastName,
				'email' => $faker->safeEmail,
				'password' => 'password',
				'phone' => '',
			]);
		}
	}

}