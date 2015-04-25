<?php

class SchedulerLocationSeeder extends Seeder {

	public function run()
	{
		$locations = [
			[
				'name' => "Ravenwood Golf Club",
				'address' => "929 Lynaugh Rd.  \r\nVictor, NY 14564",
				'phone' => '585-924-5100',
				'url' => 'http://www.ravenwoodgolf.com/',
			],
			[
				'name' => "Mill Creek Golf Club",
				'address' => "128 Cedars Ave.  \r\nRochester, NY 14428",
				'phone' => '585-889-4110',
				'url' => 'http://www.millcreekgolf.com/',
			],
		];

		foreach ($locations as $location)
		{
			LocationModel::create($location);
		}
	}

}