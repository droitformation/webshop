<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_orders', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->integer('coupon_id');
            $table->integer('shipping_id');
            $table->string('onetimeurl')->nullable(); // For download items
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
		Schema::drop('shop_orders');
	}

}
