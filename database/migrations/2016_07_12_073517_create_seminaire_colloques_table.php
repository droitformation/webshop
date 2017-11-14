<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeminaireColloquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminaire_colloques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seminaire_id')->unsigned()->index();
            $table->integer('colloque_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seminaire_colloques');
    }
}
