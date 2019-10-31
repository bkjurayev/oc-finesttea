<?php namespace Navshod\Faq\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNavshodFaqFaq extends Migration
{
    public function up()
    {
        Schema::rename('navshod_faq_', 'navshod_faq_faq');
        Schema::table('navshod_faq_faq', function($table)
        {
            $table->boolean('is_hidden')->default(1);
        });
    }
    
    public function down()
    {
        Schema::rename('navshod_faq_faq', 'navshod_faq_');
        Schema::table('navshod_faq_', function($table)
        {
            $table->dropColumn('is_hidden');
        });
    }
}