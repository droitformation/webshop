<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('price');
            $table->text('remarque')->nullable();
            $table->enum('type', ['public', 'admin'])->default('public');
            $table->string('description');
            $table->integer('rang')->nullable();
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
        Schema::dropIfExists('price_link');
    }
}
