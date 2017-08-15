<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event')->nullable();
            $table->timestamp('time')->nullable();
            $table->string('MessageID')->nullable();
            $table->string('email')->nullable();
            $table->integer('mj_campaign_id')->nullable();
            $table->integer('mj_contact_id')->nullable();
            $table->string('customcampaign')->nullable();
            $table->integer('mj_message_id')->nullable();
            $table->string('smtp_reply')->nullable();
            $table->string('CustomID')->nullable();
            $table->string('Payload')->nullable();
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
        Schema::dropIfExists('newsletter_tracking');
    }
}
