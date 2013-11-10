<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchedulerCreateStaff extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('title')->nullable();
			$table->text('bio')->nullable();
			$table->smallInteger('access');
			$table->boolean('instruction')->default(0);
			$table->timestamps();
		});

		Schema::create('staff_appointments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('staff_id')->unsigned();
			$table->integer('service_id')->unsigned();
			$table->text('location')->nullable();
			$table->date('date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
		});

		Schema::create('staff_schedules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('staff_id')->unsigned();
			$table->tinyInteger('day');
			$table->text('availability');
			$table->timestamps();
		});

		Schema::create('staff_schedules_exceptions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('staff_id')->unsigned();
			$table->date('date');
			$table->text('exceptions');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('staff');
		Schema::drop('staff_appointments');
		Schema::drop('staff_schedules');
		Schema::drop('staff_schedules_exceptions');
	}

}