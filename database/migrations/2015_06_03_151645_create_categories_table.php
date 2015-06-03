<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->tinyInteger('type');
            $table->tinyInteger('hidden');
            $table->string('sorting');
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
        Schema::drop('categories');
	}

}
