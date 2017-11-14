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
            $table->integer('price');
            $table->boolean('is_downloadable')->nullable();
            $table->string('download_link')->nullable();
            $table->tinyInteger('hidden')->nullable();
            $table->text('url')->nullable();
            $table->integer('rang')->default(0);
            $table->integer('abo_id')->nullable();
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
