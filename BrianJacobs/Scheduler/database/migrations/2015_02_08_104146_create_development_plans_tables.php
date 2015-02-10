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
		Schema::create('development_plans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('development_plans_instructors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('plan_id')->unsigned();
			$table->integer('staff_id')->unsigned();
			$table->timestamps();
		});

		Schema::create('development_plans_goals', function(Blueprint $table)
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

		Schema::create('development_plans_conversations', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('plan_id')->unsigned();
			$table->bigInteger('goal_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('content');
			$table->timestamps();
		});

		Schema::create('development_plans_goals_stats', function(Blueprint $table)
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
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('development_plans_timeline', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('plan_id')->unsigned();
			$table->integer('goal_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();
			$table->string('type');
			$table->integer('type_id')->nullable();
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
		Schema::dropIfExists('development_plans');
		Schema::dropIfExists('development_plans_instructors');
		Schema::dropIfExists('development_plans_goals');
		Schema::dropIfExists('development_plans_conversations');
		Schema::dropIfExists('development_plans_goals_stats');
		Schema::dropIfExists('development_plans_timeline');
	}

}
