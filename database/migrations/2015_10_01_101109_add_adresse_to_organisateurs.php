<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdresseToOrganisateurs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('organisateurs', function($table)
        {
            $table->string('tva');
            $table->text('adresse');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('organisateurs', function(Blueprint $table) {
            $table->dropColumn('tva');
            $table->dropColumn('adresse');
        });
	}

}
