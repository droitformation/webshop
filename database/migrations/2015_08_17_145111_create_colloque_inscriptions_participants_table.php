<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueInscriptionsParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_inscriptions_participants', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('inscription_id')->unsigned()->index();
            $table->foreign('inscription_id')->references('id')->on('colloque_inscriptions')->onDelete('cascade');
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
        Schema::drop('colloque_inscriptions_participants');
    }
}
