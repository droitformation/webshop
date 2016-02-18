<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start');
            $table->date('send_at');
            $table->string('title');
            $table->string('type');
            $table->text('text')->nullable();
            $table->string('interval');
            $table->integer('model_id')->nullable();
            $table->string('model')->nullable();
            $table->string('relation')->nullable();
            $table->integer('relation_id')->nullable();
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
        Schema::drop('reminders');
    }
}
