<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelAvisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_avis', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id')->unsigned()->index();
            $table->integer('avis_id')->unsigned()->index();
            $table->integer('rang')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_avis');
    }
}
