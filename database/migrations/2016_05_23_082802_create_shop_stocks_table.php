<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('amount');
            $table->string('operator');
            $table->string('motif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shop_stocks');
    }
}
