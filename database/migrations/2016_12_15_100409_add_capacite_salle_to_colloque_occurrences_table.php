<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCapaciteSalleToColloqueOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colloque_occurrences', function (Blueprint $table) {
            $table->integer('capacite_salle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colloque_occurrences', function (Blueprint $table) {
            $table->dropColumn('capacite_salle');
        });
    }
}
