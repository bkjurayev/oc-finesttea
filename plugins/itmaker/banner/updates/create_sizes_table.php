<?php namespace Itmaker\Banner\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSizesTable extends Migration
{
    public function up()
    {
        Schema::create('itmaker_banner_sizes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('itmaker_banner_sizes');
    }
}
