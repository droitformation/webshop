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
            $table->integer('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->integer('adresse_id')->nullable();
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
        Schema::drop('colloque_inscriptions_groupes');
    }
}
