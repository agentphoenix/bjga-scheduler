<?php

class SchedulerCategorySeeder extends Seeder {

	public function run()
	{
		$categories = array(
			array('name' => "Private Instruction"),
			array('name' => "Programs"),
			array('name' => "Events"),
			array('name' => "Teams"),
			array('name' => "Club Fitting"),
			array('name' => "Clinics and Schools"),
		);

		foreach ($categories as $category)
		{
			Category::create($category);
		}
	}

}