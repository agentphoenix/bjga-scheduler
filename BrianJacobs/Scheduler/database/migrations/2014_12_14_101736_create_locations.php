<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('address');
			$table->string('phone');
			$table->string('url');
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::table('services', function(Blueprint $table)
		{
			$table->dropColumn('location');
			$table->integer('location_id')->nullable()->after('description');
		});

		Schema::table('staff_appointments', function(Blueprint $table)
		{
			$table->integer('location_id')->after('service_id');
		});

		Schema::table('staff_appointments_recurring', function(Blueprint $table)
		{
			$table->integer('location_id')->after('service_id');
		});

		Schema::table('staff_schedules', function(Blueprint $table)
		{
			$table->integer('location_id')->after('availability');
		});

		// Get staff instructors
		$instructors = StaffModel::where('instruction', (int) true)->get();

		foreach ($instructors as $instructor)
		{
			foreach ($instructor->schedule as $day)
			{
				$day->fill(['location_id' => 1])->save();
			}
		}

		$this->populateTables();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('locations');
	}

	protected function populateTables()
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