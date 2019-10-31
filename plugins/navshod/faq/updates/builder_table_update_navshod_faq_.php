<?php namespace Navshod\Faq\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNavshodFaq extends Migration
{
    public function up()
    {
        Schema::table('navshod_faq_', function($table)
        {
            $table->text('answer');
            $table->renameColumn('message', 'question');
        });
    }
    
    public function down()
    {
        Schema::table('navshod_faq_', function($table)
        {
            $table->dropColumn('answer');
            $table->renameColumn('question', 'message');
        });
    }
}
