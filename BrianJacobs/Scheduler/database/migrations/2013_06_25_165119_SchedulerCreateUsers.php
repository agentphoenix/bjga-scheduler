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
			$table->bigInteger('occurrence_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();
			$table->boolean('paid')->default(0);
			$table->string('amount', 10);
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
		Schema::drop('users');
		Schema::drop('users_appointments');
	}

	protected function populateTables()
	{
		$users = [
			[
				'name' => "Brian Jacobs",
				'email' => "bjacobs1@rochester.rr.com",
				'password' => "nikegolf",
				'phone' => '585-415-9323',
				'address' => '284 Chambers St. Spencerport, NY',
			],
			[
				'name' => "David VanScott",
				'email' => "david.vanscott@gmail.com",
				'password' => "alpha312",
				'phone' => '585-576-8260',
				'address' => '2145 East Ave. Apt H Rochester, NY 14610',
			],
		];

		foreach ($users as $user)
		{
			UserModel::create($user);
		}

		$staff = [
			['user_id' => 1, 'access' => 3, 'title' => "Director of Instruction", 'instruction' => (int) true],
			['user_id' => 2, 'access' => 4, 'title' => "Web Developer"],
		];

		foreach ($staff as $s)
		{
			$item = StaffModel::create($s);

			// Create general availability
			for ($d = 0; $d <=6; $d++)
			{
				StaffScheduleModel::create([
					'staff_id'		=> $item->id,
					'day'			=> $d,
					'availability'	=> '9:00-17:00',
				]);
			}
		}
	}

}