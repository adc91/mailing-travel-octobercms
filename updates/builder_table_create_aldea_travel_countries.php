<?php namespace Aldea\Travel\Updates;

use Schema;
use Db;
use File;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelCountries extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_countries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('code', 2);
            $table->string('name', 200);
        });

        DB::unprepared(File::get(dirname(__FILE__) . '/queries/aldea_travel_countries.sql'));
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_countries');
    }
}
