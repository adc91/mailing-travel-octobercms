<?php namespace Aldea\Travel\Updates;

use Schema;
use DB;
use File;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelGroups extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 200);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        DB::unprepared(File::get(dirname(__FILE__) . '/queries/aldea_travel_groups.sql'));
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_groups');
    }
}
