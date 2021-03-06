<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		//$this->call('SchedulerServiceSeeder');
		//$this->call('SchedulerUserSeeder');
		//$this->call('SchedulerLocationSeeder');
		//$this->call('SchedulerCustomerSeeder');
		//$this->call('PlanSeeder');

		if (App::environment() != 'production')
		{
			$this->call('UserSeeder');
			$this->call('StaffSeeder');
		}
	}

}