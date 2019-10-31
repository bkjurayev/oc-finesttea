<?php
/**
 * Banner Theme
 * Created by alvaro.
 * User: alvaro
 * Date: 29/03/18
 * Time: 08:24 AM
 */

namespace Itmaker\Banner\Updates;


use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class add_text_pos_column_banners_table
 *
 * @package Itmaker\Banner\Updates
 */
class AddTextPosColumnBannersTable extends Migration
{
    protected $tablename = 'itmaker_banner_banners';

    public function up()
    {
        Schema::table(
            $this->tablename,
            function ($table) {
                $table->string('text_pos', 100)->nullable();
            }
        );
    }

    public function down()
    {
        if (Schema::hasColumn($this->tablename, 'text_pos')) {
            Schema::table(
                $this->tablename,
                function ($table) {
                    $table->dropColumn('text_pos');
                }
            );
        }
    }

}