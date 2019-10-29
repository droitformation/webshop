<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {

            $table->increments('id');

            $table->nestedSet();
            $table->integer('depth')->nullable();

            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('slug');
            $table->string('menu_title');
            $table->integer('rang')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('hidden')->nullable();
            $table->integer('site_id')->nullable();
            $table->string('template')->nullable();

            $table->text('url')->nullable();
            $table->tinyInteger('isExternal')->nullable();

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
        Schema::drop('pages');
    }
}
