<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColloquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colloques', function(Blueprint $table) {

            $table->increments('id');
            $table->string('titre');
            $table->string('soustitre')->nullable();
            $table->string('sujet');
            $table->string('url')->nullable();
            $table->text('remarques')->nullable();
            $table->date('start_at');
            $table->date('end_at')->nullable();
            $table->date('registration_at');
            $table->date('active_at')->nullable();
            $table->integer('organisateur_id');
            $table->integer('location_id');
            $table->integer('compte_id')->nullable();
            $table->enum('visible', ['0','1'])->default(0);
            $table->tinyInteger('bon')->default(1);
            $table->tinyInteger('facture')->default(1);
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
        Schema::drop('colloques');
    }
}
