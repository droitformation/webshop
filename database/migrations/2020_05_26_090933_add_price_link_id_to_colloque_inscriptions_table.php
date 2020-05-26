<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceLinkIdToColloqueInscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colloque_inscriptions', function (Blueprint $table) {
            $table->integer('price_link_id')->nullable();
            $table->integer('price_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colloque_inscriptions', function (Blueprint $table) {
            $table->dropColumn('price_link_id');
            $table->integer('price_id')->change();
        });
    }
}
