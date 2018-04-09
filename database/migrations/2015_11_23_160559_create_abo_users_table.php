<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAboUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abo_users', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('abo_id');
            $table->integer('numero');
            $table->integer('exemplaires');
            $table->integer('adresse_id');
            $table->integer('tiers_id')->nullable();
            $table->integer('price')->nullable();
            $table->string('reference')->nullable();
            $table->text('remarque')->nullable();

            $table->string('status')->default('abonne');
            $table->string('raison')->nullable();
            $table->enum('renouvellement',['auto','year'])->default('auto');

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
        Schema::drop('abo_users');
    }
}
