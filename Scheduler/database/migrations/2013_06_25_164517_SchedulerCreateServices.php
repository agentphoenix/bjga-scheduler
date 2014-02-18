<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchedulerCreateServices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('services', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('staff_id')->unsigned();
			$table->string('category');
			$table->integer('order')->default(99);
			$table->boolean('status')->default(1);
			$table->string('name');
			$table->string('slug')->nullable()->unique();
			$table->text('description')->nullable();
			$table->text('location')->nullable();
			$table->string('price')->nullable();
			$table->integer('occurrences')->nullable();
			$table->integer('occurrences_schedule')->default(0);
			$table->integer('duration')->nullable();
			$table->integer('user_limit')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('services_occurrences', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('service_id')->unsigned();
			$table->datetime('start')->nullable();
			$table->datetime('end')->nullable();
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
		Schema::drop('services');
		Schema::drop('services_occurrences');
	}

}