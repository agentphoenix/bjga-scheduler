<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCredits extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_credits', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('code', 12);
			$table->string('type');
			$table->integer('value')->default(0);
			$table->integer('claimed')->default(0);
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('email')->nullable();
			$table->timestamp('expires')->nullable();
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
		Schema::dropIfExists('users_credits');
	}

}
