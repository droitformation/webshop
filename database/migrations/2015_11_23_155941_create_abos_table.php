<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->enum('plan',['month','semester','year'])->default('year');
            $table->string('logo')->nullable();
            $table->string('name')->nullable();
            $table->string('compte')->nullable();
            $table->integer('price');
            $table->integer('shipping')->nullable();
            $table->text('adresse')->nullable();
            $table->string('email')->nullable();
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
        Schema::drop('abos');
    }
}
