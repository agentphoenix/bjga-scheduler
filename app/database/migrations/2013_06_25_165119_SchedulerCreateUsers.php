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
		});

		Schema::create('users_appointments', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('appointment_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->boolean('gift_certificate')->default(0);
			$table->integer('gift_certificate_amount')->default(0);
			$table->string('payment_type')->default('money');
			$table->boolean('paid')->default(0);
			$table->integer('amount');
		});

		Schema::create('users_credits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('type')->default('hours');
			$table->integer('amount');
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
		Schema::drop('users');
		Schema::drop('users_appointments');
		Schema::drop('users_credits');
	}

}