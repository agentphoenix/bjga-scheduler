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
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('description')->nullable();
			$table->timestamps();
		});

		Schema::create('services', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('staff_id')->unsigned();
			$table->string('name');
			$table->string('slug')->nullable()->unique();
			$table->text('description')->nullable();
			$table->string('price')->nullable();
			$table->integer('occurrences')->nullable();
			$table->integer('duration')->nullable();
			$table->text('additional_services')->nullable();
			$table->integer('user_limit')->nullable();
			$table->integer('lead_out')->default(15);
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('services_occurrences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('service_id')->unsigned();
			$table->date('date');
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
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
		Schema::drop('categories');
		Schema::drop('services');
		Schema::drop('services_occurrences');
	}

}