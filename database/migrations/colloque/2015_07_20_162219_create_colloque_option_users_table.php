<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueOptionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_option_users', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('inscription_id');
            $table->integer('option_id');
            $table->integer('groupe_id')->nullable();
            $table->text('reponse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_option_users');
    }
}
