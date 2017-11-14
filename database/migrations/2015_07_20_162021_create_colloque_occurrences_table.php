<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_occurrences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->date('starting_at');
            $table->integer('colloque_id');
            $table->integer('lieux_id');
            $table->tinyInteger('full')->nullable();
            $table->timestamps();
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
        Schema::drop('colloque_occurrences');
    }
}
