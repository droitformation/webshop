<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloqueInscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloque_inscriptions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('colloque_id');
            $table->integer('user_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('inscription_no')->nullable();
            $table->integer('price_id');
            $table->date('payed_at')->nullable();
            $table->date('send_at')->nullable();
            $table->enum('status', ['pending', 'payed', 'free'])->default('pending');
            $table->tinyInteger('admin')->nullable();
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
        Schema::drop('colloque_inscriptions');
    }
}
