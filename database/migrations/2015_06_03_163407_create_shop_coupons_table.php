<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('shop_coupons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('value')->nullable();
            $table->string('title');
            $table->string('type')->default('global');
            $table->dateTime('expire_at');
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
        Schema::drop('shop_coupons');
	}

}
