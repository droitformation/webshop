<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalculetteTauxTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calculette_taux', function(Blueprint $table) {
			$table->increments('id');
			$table->string('canton');		
			$table->dateTime('start_at');
			$table->double('taux', 11, 2);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calculette_taux');
	}

}
