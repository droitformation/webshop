<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToNewsletterAboUsers extends Migration
{

    public function __construct()
    {
        \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abo_users', function (Blueprint $table) {
            $table->integer('user_id')->after('adresse_id')->nullable();
            $table->integer('tiers_user_id')->after('user_id')->nullable();
            $table->integer('adresse_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abo_users', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('tiers_user_id');
            $table->integer('adresse_id')->change();
        });
    }
}
