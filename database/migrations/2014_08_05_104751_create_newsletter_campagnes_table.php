<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsletterCampagnesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletter_campagnes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('sujet');
            $table->string('auteurs')->nullable();
            $table->integer('newsletter_id');
            $table->integer('api_campagne_id');
            $table->integer('job_id')->nullable();
            $table->enum('status', array('brouillon', 'envoyé'));
			$table->tinyInteger('hidden')->nullable();
            $table->integer('old_id')->nullable();
            $table->softDeletes();
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
		Schema::drop('newsletter_campagnes');
	}

}
