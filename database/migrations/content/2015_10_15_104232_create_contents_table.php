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
			$table->string('titre')->nullable();
			$table->text('contenu')->nullable();
			$table->string('image')->nullable();
			$table->string('url')->nullable();
            $table->integer('rang')->default(0);
			$table->enum('type', array('pub','texte','soutien'));
			$table->enum('position', array('sidebar','home-bloc','home-colonne'));
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
