<?php

class SchedulerUserSeeder extends Seeder {

	public function run()
	{
		$users = array(
			array(
				'name' => "Brian Jacobs",
				'email' => "bjacobs1@rochester.rr.com",
				'password' => "nikegolf",
			),
			array(
				'name' => "David VanScott",
				'email' => "david.vanscott@gmail.com",
				'password' => "alpha312",
			),
		);

		foreach ($users as $user)
		{
			User::create($user);
		}

		$staff = array(
			array('user_id' => 1, 'access' => 2, 'title' => "Senior Instructor", 'instruction' => (int) true),
			array('user_id' => 2, 'access' => 3, 'title' => "Web Developer"),
		);

		foreach ($staff as $s)
		{
			$item = Staff::create($s);

			// Create general availability
			for ($d = 0; $d <=6; $d++)
			{
				Schedule::create(array(
					'staff_id'		=> $item->id,
					'day'			=> $d,
					'availability'	=> '',
				));
			}
		}
	}

}