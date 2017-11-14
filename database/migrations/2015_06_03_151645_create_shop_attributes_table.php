<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('shop_attributes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('duration');
			$table->tinyInteger('reminder')->nullable();
            $table->text('text')->nullable();
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
        Schema::drop('shop_attributes');
	}

}
