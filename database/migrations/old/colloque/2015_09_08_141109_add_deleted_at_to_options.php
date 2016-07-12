<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('colloque_options', function($table)
        {
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('colloque_option_users', function($table)
        {
            $table->softDeletes();
        });

        Schema::table('colloque_inscriptions_participants', function($table)
        {
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
        Schema::table('colloque_options', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
        });

        Schema::table('colloque_option_users', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('colloque_inscriptions_participants', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
	}

}
