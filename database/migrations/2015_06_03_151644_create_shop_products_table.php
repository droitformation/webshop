<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('shop_products', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('teaser')->nullable();
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('weight')->nullable();
            $table->integer('sku');
            $table->decimal('price', 6, 2);
            $table->boolean('is_downloadable')->default(false);
            $table->tinyInteger('hidden');
            $table->softDeletes();
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
        Schema::drop('shop_products');
	}

}
