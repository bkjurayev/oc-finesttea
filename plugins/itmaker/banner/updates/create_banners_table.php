<?php namespace Itmaker\Banner\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('itmaker_banner_banners', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
            $table->string('pretitle')->nullable();
            $table->text('title');
            $table->text('subtitle')->nullable();
            $table->integer('product_id')->nullable()->unsigned();
            $table->string('page', 20)->nullable();
            $table->string('url')->nullable();
            $table->string('offer')->nullable();
            $table->string('button_label')->nullable();
            $table->string('bottom_caption')->nullable();
            $table->integer('size_id')->nullable()->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('itmaker_banner_banners');
    }
}
