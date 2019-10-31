<?php
/**
 * Banner Theme
 * Created by alvaro.
 * User: alvaro
 * Date: 29/03/18
 * Time: 06:58 AM
 */

namespace Itmaker\Banner\Updates;


use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class add_link_type_column_banners_table
 *
 * @package Itmaker\Banner\Updates
 */
class AddLinkTypeColumnBannersTable extends Migration
{
    protected $tablename = 'itmaker_banner_banners';

    public function up()
    {
        Schema::table(
            $this->tablename,
            function ($table) {
                $table->string('link_type', 100)->nullable();
            }
        );
    }

    public function down()
    {
        if (Schema::hasColumn($this->tablename, 'link_type')) {
            Schema::table(
                $this->tablename,
                function ($table) {
                    $table->dropColumn('link_type');
                }
            );
        }
    }

}