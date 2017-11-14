<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDuplicatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_duplicates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name');
            $table->string('last_name');
			$table->string('name');
			$table->string('username');
			$table->string('email');
			$table->string('oldpassword', 60);
			$table->integer('user_id')->nullable();
			$table->integer('old_id')->nullable();
			$table->tinyInteger('hidden')->nullable();
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
		Schema::drop('user_duplicates');
	}

}
