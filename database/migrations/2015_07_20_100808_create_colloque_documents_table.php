<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_documents', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id');
            $table->enum('display', ['0', '1'])->default('1');
            $table->enum('type', ['illustration','programme','document']);
            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_documents');
    }
}
