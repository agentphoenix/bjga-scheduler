<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchedulerCreateUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->string('phone')->nullable();
			$table->text('address')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('users_appointments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('appointment_id')->unsigned();
			$table->bigInteger('recur_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();
			$table->boolean('has_gift')->default(0);
			$table->integer('gift_amount')->default(0);
			$table->boolean('paid')->default(0);
			$table->integer('amount');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
		Schema::drop('users_appointments');
	}

}