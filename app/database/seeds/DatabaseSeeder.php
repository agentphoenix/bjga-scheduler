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

		$this->call('SchedulerCategorySeeder');
		$this->call('SchedulerServiceSeeder');
		$this->call('SchedulerSettingSeeder');
		$this->call('SchedulerUserSeeder');
		$this->call('SchedulerCustomerSeeder');
	}

}