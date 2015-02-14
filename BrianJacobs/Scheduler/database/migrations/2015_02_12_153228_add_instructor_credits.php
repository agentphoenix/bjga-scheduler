<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstructorCredits extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_credits', function(Blueprint $table)
		{
			$table->integer('staff_id')->unsigned()->after('expires');
		});

		// Update any existing credits
		$all = CreditModel::all();

		if ($all->count() > 0)
		{
			foreach ($all as $credit)
			{
				$credit->update(['staff_id' => 1]);
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_credits', function(Blueprint $table)
		{
			$table->dropColumn('staff_id');
		});
	}

}
