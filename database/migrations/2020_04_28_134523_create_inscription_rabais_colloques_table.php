<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionRabaisColloquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscription_rabais_colloques', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rabais_id')->unsigned()->index();
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
        Schema::dropIfExists('inscription_rabais_colloques');
    }
}
