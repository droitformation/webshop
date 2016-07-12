<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccurrenceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_occurrence_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inscription_id');
            $table->integer('occurrence_id');
            $table->tinyInteger('present')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_occurrence_users');
    }
}
