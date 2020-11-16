<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemRegistriesTable extends Migration {

  
    public function up()
    {
        Schema::create(\Config::get('registry.table', 'system_registries'), function(Blueprint $table)
        {
            $table->string('key');
            $table->text('value');

            $table->primary('key');
        });
    }


    public function down()
    {
        Schema::drop(\Config::get('registry.table', 'system_registries'));
    }

}
