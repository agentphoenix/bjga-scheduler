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
			$table->boolean('loyalty')->default(0);
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

		$this->populateTables();
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

	protected function populateTables()
	{
		$services = array(
			array(
				'category'			=> '',
				'staff_id'			=> 0,
				'order'				=> 0,
				'name'				=> "BLOCKED",
				'price'				=> 0,
				'occurrences'		=> 1,
				'duration'			=> 0,
				'user_limit'		=> 0,
			),
			array(
				'category'			=> 'lesson',
				'staff_id'			=> 1,
				'order'				=> 1,
				'name'				=> "Private Lesson",
				'price'				=> '125',
				'occurrences'		=> 1,
				'duration'			=> 60,
				'user_limit'		=> 1,
			),
			array(
				'category'			=> 'lesson',
				'staff_id'			=> 1,
				'order'				=> 2,
				'name'				=> "9-hole Playing Lesson",
				'price'				=> '275',
				'occurrences'		=> 1,
				'duration'			=> 180,
				'user_limit'		=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'order'					=> 3,
				'name'					=> "Performance Series (3 Months)",
				'price'					=> '70',
				'occurrences'			=> 12,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'order'					=> 4,
				'name'					=> "Performance Series (6 Months)",
				'price'					=> '65',
				'occurrences'			=> 24,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
			array(
				'category'				=> 'lesson',
				'staff_id'				=> 1,
				'order'					=> 5,
				'name'					=> "Performance Series (12 Months)",
				'price'					=> '60',
				'occurrences'			=> 48,
				'occurrences_schedule'	=> 7,
				'duration'				=> 60,
				'user_limit'			=> 1,
			),
		);

		foreach ($services as $service)
		{
			ServiceModel::create($service);
		}
	}

}