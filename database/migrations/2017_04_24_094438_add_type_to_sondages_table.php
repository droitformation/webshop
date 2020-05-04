<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToSondagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sondages', function (Blueprint $table) {
            $table->integer('colloque_id')->nullable()->change();
            $table->tinyInteger('marketing')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('signature')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sondages', function (Blueprint $table) {
            $table->integer('colloque_id')->change();
            $table->dropColumn('marketing');
            $table->dropColumn('title');
            $table->dropColumn('description');
        });
    }
}
