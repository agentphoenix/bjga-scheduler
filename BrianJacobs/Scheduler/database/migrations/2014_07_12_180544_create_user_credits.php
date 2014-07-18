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
			$table->string('type', 20);
			$table->float('value')->default(0);
			$table->float('claimed')->default(0);
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('email', 100)->nullable();
			$table->text('notes')->nullable();
			$table->timestamp('expires')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::table('users_appointments', function(Blueprint $table)
		{
			$table->float('received')->default(0)->after('amount');
		});

		DB::query("ALTER TABLE `scheduler_users_appointments` ALTER `amount` DROP DEFAULT;");
		DB::query("ALTER TABLE `scheduler_users_appointments` CHANGE COLUMN `amount` `amount` FLOAT(8,2) NOT NULL COLLATE 'utf8_unicode_ci' AFTER `paid`;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users_credits');

		if (Schema::hasColumn('users_appointments', 'received'))
		{
			Schema::table('users_appointments', function(Blueprint $table)
			{
				$table->dropColumn('received');
			});
		}
	}

}
