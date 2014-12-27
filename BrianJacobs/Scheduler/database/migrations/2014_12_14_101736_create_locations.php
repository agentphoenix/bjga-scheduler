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

}