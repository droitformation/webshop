<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnalysesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analyses', function(Blueprint $table)
		{
            $table->increments('id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->string('authors')->nullable();
            $table->integer('pub_date');
            $table->text('abstract')->nullable();
            $table->text('file')->nullable();
            $table->integer('categories')->nullable();
            $table->integer('arrets')->nullable();
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
		Schema::drop('analyses');
	}

}
