<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlanTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans_goals_completion', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('goal_id')->unsigned();
			$table->date('target_date')->nullable();
			$table->integer('count')->nullable();
			$table->string('type')->nullable();
			$table->string('metric')->nullable();
			$table->string('operator')->nullable();
			$table->string('value')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('plans_goals_completion');
	}

	protected function migrateExistingData()
	{
		// Get all of the stats
		$stats = Stat::all();

		if ($stats->count() > 0)
		{
			foreach ($stats as $stat)
			{
				$stat->goals()->attach($stat->goal_id);
			}
		}
	}

}
