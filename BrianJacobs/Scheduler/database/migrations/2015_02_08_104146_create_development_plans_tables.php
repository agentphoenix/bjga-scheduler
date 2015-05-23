<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopmentPlansTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('plans_instructors', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('plan_id')->unsigned();
			$table->integer('staff_id')->unsigned();
		});

		Schema::create('plans_goals', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('plan_id')->unsigned();
			$table->string('title');
			$table->text('summary');
			$table->boolean('completed')->default((int) false);
			$table->timestamp('completed_date')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('plans_conversations', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('goal_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('content');
			$table->timestamps();
		});

		Schema::create('plans_goals_stats', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('goal_id')->unsigned();
			$table->string('type', 25)->default('round');
			$table->string('course')->nullable();
			$table->string('score')->nullable();
			$table->integer('fir')->nullable();
			$table->integer('gir')->nullable();
			$table->integer('putts')->nullable();
			$table->integer('penalties')->nullable();
			$table->integer('minutes')->nullable();
			$table->integer('balls')->nullable();
			$table->integer('holes')->nullable();
			$table->integer('players')->nullable();
			$table->integer('place')->nullable();
			$table->string('icon')->nullable();
			$table->string('tournament')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::table('staff_appointments', function(Blueprint $table)
		{
			$table->bigInteger('plan_goal_id')->unsigned()->nullable()->after('occurrence_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('plans');
		Schema::dropIfExists('plans_instructors');
		Schema::dropIfExists('plans_goals');
		Schema::dropIfExists('plans_conversations');
		Schema::dropIfExists('plans_goals_stats');

		Schema::table('staff_appointments', function(Blueprint $table)
		{
			$table->dropColumn('plan_goal_id');
		});
	}

}
