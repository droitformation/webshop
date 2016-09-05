<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adresses', function(Blueprint $table) {
			$table->increments('id');
            $table->integer('user_id')->nullable();
			$table->integer('civilite_id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->nullable();
			$table->string('company')->nullable();
			$table->string('role')->nullable();
			$table->integer('profession_id')->nullable();
			$table->string('telephone')->nullable();
			$table->string('mobile')->nullable();
			$table->string('fax')->nullable();
			$table->text('adresse')->nullable();
			$table->string('cp')->nullable();
			$table->string('complement')->nullable();
			$table->string('npa')->nullable();
			$table->string('ville')->nullable();
			$table->integer('canton_id')->nullable();
			$table->integer('pays_id')->nullable();
            $table->string('type')->default(1)->nullable();
			$table->boolean('livraison')->nullable();
			$table->string('poste')->nullable();
			$table->tinyInteger('hidden')->nullable();
			$table->integer('old_id')->nullable();
			$table->integer('duplicate_id')->nullable();
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
		Schema::drop('adresses');
	}

}
