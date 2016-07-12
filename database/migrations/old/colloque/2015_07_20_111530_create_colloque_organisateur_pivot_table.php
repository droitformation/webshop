<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueOrganisateurPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_organisateurs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id')->unsigned()->index();
            $table->integer('organisateur_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_organisateurs');
    }
}
