<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('content')->nullable();
			$table->string('image')->nullable();
			$table->string('url')->nullable();
            $table->integer('rang')->default(0);
			$table->integer('page_id');
			$table->integer('categorie_id')->nullable();
			$table->enum('type', array('agenda','loi','faq','lien','autorite'));
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
