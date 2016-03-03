<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueAttestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_attestations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id');
            $table->string('telephone');
            $table->string('lieu');
            $table->string('organisateur');
            $table->string('title');
            $table->string('signature');
            $table->text('comment')->nullable();
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
        Schema::drop('colloque_attestations');
    }
}
