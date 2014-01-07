<?php

class SchedulerServiceSeeder extends Seeder {

	public function run()
	{
		$services = array(
			array(
				'category'			=> 'lesson',
				'staff_id'			=> 1,
				'name'				=> "Private Lesson",
				'price'				=> '125',
				'occurrences'		=> 1,
				'duration'			=> 60,
				'user_limit'		=> 1,
			),
			array(
				'category'			=> 'lesson',
				'staff_id'			=> 1,
				'name'				=> "9-hole Playing Lesson",
				'price'				=> '275',
				'occurrences'		=> 1,
				'duration'			=> 180,
				'user_limit'		=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'name'					=> "Performance Series (3 Months)",
				'price'					=> '70',
				'occurrences'			=> 12,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'name'					=> "Performance Series (6 Months)",
				'price'					=> '65',
				'occurrences'			=> 24,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'name'					=> "Performance Series (12 Months)",
				'price'					=> '60',
				'occurrences'			=> 48,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
		);

		foreach ($services as $service)
		{
			ServiceModel::create($service);
		}
	}

}