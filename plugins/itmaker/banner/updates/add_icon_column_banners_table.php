<?php
/**
 * Banner Theme
 * Created by alvaro.
 * User: alvaro
 * Date: 29/03/18
 * Time: 06:03 AM
 */

namespace Itmaker\Banner\Updates;


use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class add_icon_column_banners_table
 *
 * @package Itmaker\Banner\Updates
 */
class AddIconColumnBannersTable extends Migration
{
    protected $tablename = 'itmaker_banner_banners';

    public function up()
    {
        Schema::table(
            $this->tablename,
            function ($table) {
                $table->string('icon', 100)->nullable();
            }
        );
    }

    public function down()
    {
        if (Schema::hasColumn($this->tablename, 'icon')) {
            Schema::table(
                $this->tablename,
                function ($table) {
                    $table->dropColumn('icon');
                }
            );
        }
    }

}