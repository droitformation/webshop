<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlocsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blocs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('content')->nullable();
			$table->string('image')->nullable();
			$table->string('url')->nullable();
			$table->string('slug')->nullable();
            $table->integer('rang')->default(0);
			$table->integer('page_id');
			$table->integer('site_id')->nullable();
			$table->enum('type', ['soutien','pub','text']);
			$table->enum('position', ['sidebar','page']);
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
		Schema::drop('contents');
	}

}
