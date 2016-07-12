<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueSpecialisationsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_specialisations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id')->unsigned()->index();
            $table->integer('specialisation_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_specialisations');
    }
}
