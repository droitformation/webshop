<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProduitAuthorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('shop_product_authors', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('shop_products');
            $table->integer('author_id');
            $table->integer('sorting');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('shop_product_authors');
	}

}
