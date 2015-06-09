<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopShippingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('shop_shipping', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('value');
            $table->string('price');
            $table->string('type');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('shop_shipping');
	}

}
