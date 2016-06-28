<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosteToAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adresses', function(Blueprint $table) {
            $table->string('poste')->nullable();
            $table->tinyInteger('hidden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adresses', function(Blueprint $table) {
            $table->dropColumn('poste');
            $table->dropColumn('hidden');
        });
    }
}
