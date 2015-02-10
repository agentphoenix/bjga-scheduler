<?php

class PlanSeeder extends Seeder {

	public function run()
	{
		// Create a new instance of Faker
		$faker = Faker\Factory::create();

		// Create a plan
		$plan = Plan::create(['user_id' => 2]);

		// Attach an instructor
		//$plan->instructors()->attach(1);

		// Create some goals
		$goals = [
			new Goal([
				'plan_id'		=> 1,
				'title'			=> ucwords(implode(' ', $faker->words(mt_rand(4, 10)))),
				'summary'		=> $faker->text(mt_rand(25, 200)),
				'created_at'	=> $faker->dateTimeBetween('-1 years'),
				'updated_at'	=> $faker->dateTimeBetween('-1 years'),
			]),
			new Goal([
				'plan_id'		=> 1,
				'title'			=> ucwords(implode(' ', $faker->words(mt_rand(4, 10)))),
				'summary'		=> $faker->text(mt_rand(25, 200)),
				'created_at'	=> $faker->dateTimeBetween('-1 years'),
				'updated_at'	=> $faker->dateTimeBetween('-1 years'),
			]),
			new Goal([
				'plan_id'		=> 1,
				'title'			=> ucwords(implode(' ', $faker->words(mt_rand(4, 10)))),
				'summary'		=> $faker->text(mt_rand(25, 200)),
				'created_at'	=> $faker->dateTimeBetween('-1 years'),
				'updated_at'	=> $faker->dateTimeBetween('-1 years'),
			]),
			new Goal([
				'plan_id'		=> 1,
				'title'			=> ucwords(implode(' ', $faker->words(mt_rand(4, 10)))),
				'summary'		=> $faker->text(mt_rand(25, 200)),
				'created_at'	=> $faker->dateTimeBetween('-1 years'),
				'updated_at'	=> $faker->dateTimeBetween('-1 years'),
			]),
		];

		// Attach the goals to the plan
		$plan->goals()->saveMany($goals);

		// Create some conversations
		for ($i=0; $i<15; $i++)
		{
			$date = $faker->dateTimeBetween('-1 years');

			Conversation::create([
				'goal_id'		=> mt_rand(1, 4),
				'user_id'		=> mt_rand(1, 2),
				'content'		=> $faker->text(mt_rand(10, 300)),
				'created_at'	=> $date,
				'updated_at'	=> $date,
			]);
		}
	}

}