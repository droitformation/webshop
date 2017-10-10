<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobHashToNewsletterCampagnes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_campagnes', function (Blueprint $table) {
            $table->integer('job_id')->after('api_campagne_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_campagnes', function (Blueprint $table) {
            $table->dropColumn('job_id');
        });
    }
}
