<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderIdToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function($table)
        {
            $table->string('username');
            $table->string('name');
            $table->string('provider');
            $table->string('provider_id')->unique();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');
            $table->dropColumn('username');
            $table->dropColumn('name');
        });
	}

}
