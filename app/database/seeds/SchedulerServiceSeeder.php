<?php

class SchedulerServiceSeeder extends Seeder {

	public function run()
	{
		$services = array(
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "60-minute Private Lesson",
				'price'					=> '125.00',
				'occurrences'			=> 1,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "Private Instruction Bundle A",
				'description'			=> "10 hours of private instruction and two 9-hole playing lessons with follow-up after the round",
				'price'					=> '1650.00',
				'occurrences'			=> 10,
				'duration'				=> 60,
				'additional_services'	=> '5,2',
				'user_limit'			=> 1,
			),
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "Private Instruction Bundle B",
				'description'			=> "5 hours of private instruction and one 9-hole playing lessons with follow-up after the round",
				'price'					=> '825.00',
				'occurrences'			=> 5,
				'duration'				=> 60,
				'additional_services'	=> '5,1',
				'user_limit'			=> 1,
			),
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "Private Instruction Bundle C",
				'description'			=> "3 hours of private instruction and one 9-hole playing lessons with follow-up after the round",
				'price'					=> '675.00',
				'occurrences'			=> 3,
				'duration'				=> 60,
				'additional_services'	=> '5,1',
				'user_limit'			=> 1,
			),
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "1:1 Half Day Golf School",
				'price'					=> '275.00',
				'occurrences'			=> 1,
				'duration'				=> 180,
				'user_limit'			=> 1,
			),
			array(
				'category_id'			=> 1,
				'staff_id'				=> 1,
				'name'					=> "1:1 Full Day Golf School",
				'price'					=> '500.00',
				'occurrences'			=> 1,
				'duration'				=> 300,
				'user_limit'			=> 1,
			),

			array(
				'category_id'			=> 3,
				'staff_id'				=> 1,
				'name'					=> "Fix Your Slice Forever",
				'price'					=> '50.00',
				'occurrences'			=> 1,
				'duration'				=> 300,
				'user_limit'			=> 25,
				'description'			=> "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut gravida id magna at porttitor. Pellentesque convallis lacinia urna nec viverra. Praesent tincidunt pharetra varius. Proin eget libero et sapien tempus suscipit ac quis dolor. Nam et dapibus metus. In pretium, diam ac adipiscing venenatis, sem risus vehicula leo, quis elementum.",
			),
			array(
				'category_id'			=> 3,
				'staff_id'				=> 1,
				'name'					=> "Storm the Course - 2013",
				'price'					=> 'Free',
				'occurrences'			=> 1,
				'duration'				=> 300,
				'user_limit'			=> 25,
				'description'			=> "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut gravida id magna at porttitor. Pellentesque convallis lacinia urna nec viverra. Praesent tincidunt pharetra varius. Proin eget libero et sapien tempus suscipit ac quis dolor. Nam et dapibus metus. In pretium, diam ac adipiscing venenatis, sem risus vehicula leo, quis elementum.",
			),
		);

		foreach ($services as $service)
		{
			Service::create($service);
		}
	}

}