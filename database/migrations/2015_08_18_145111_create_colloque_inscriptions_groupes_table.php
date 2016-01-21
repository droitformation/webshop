<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueInscriptionsGroupesTable extends Migration
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
            $table->string('description')->nullable();
            $table->integer('adresse_id')->unsigned()->index();
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
        Schema::drop('colloque_inscriptions_groupes');
    }
}
