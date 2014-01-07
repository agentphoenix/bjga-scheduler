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
			$table->softDeletes();
		});

		Schema::create('staff_appointments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('staff_id')->unsigned();
			$table->integer('service_id')->unsigned();
			$table->bigInteger('recur_id')->unsigned()->nullable();
			$table->datetime('start')->nullable();
			$table->datetime('end')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('staff_appointments_recurring', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('staff_id')->unsigned();
			$table->integer('service_id')->unsigned();
			$table->datetime('start')->nullable();
			$table->datetime('end')->nullable();
		});

		Schema::create('staff_schedules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('staff_id')->unsigned();
			$table->tinyInteger('day');
			$table->text('availability');
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
		Schema::drop('staff_appointments_recurring');
		Schema::drop('staff_schedules');
	}

}