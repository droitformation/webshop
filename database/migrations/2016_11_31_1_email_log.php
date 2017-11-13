<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('email_log')) {
            Schema::create('email_log', function (Blueprint $table) {
                $table->increments('id');
                $table->dateTime('date');
                $table->string('to');
                $table->string('subject');
                $table->text('body');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('email_log')) {
            Schema::drop('email_log');
        }
    }
}
