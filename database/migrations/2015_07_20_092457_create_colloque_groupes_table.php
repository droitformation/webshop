<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_inscriptions_groupes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id');
            $table->integer('user_id');
            $table->string('description');
            $table->integer('adresse_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_inscriptions_groupes');
    }
}
