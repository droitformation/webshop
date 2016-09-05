<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloquePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_prices', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id');
            $table->integer('price');
            $table->text('remarque')->nullable();
            $table->enum('type', ['public', 'admin'])->default('public');
            $table->string('description');
            $table->integer('rang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('colloque_prices');
    }
}
