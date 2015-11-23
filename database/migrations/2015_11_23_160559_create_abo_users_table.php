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
            $table->integer('tiers_id');
            $table->integer('price');
            $table->string('reference');
            $table->text('remarque');

            $table->enum('status',['abonne','gratuit'])->default('abonne');
            $table->enum('renouvellement',['auto','year'])->default('auto');
            $table->enum('plan',['month','semester','year'])->default('year');

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
