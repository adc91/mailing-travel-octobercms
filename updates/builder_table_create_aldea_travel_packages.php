<?php namespace Aldea\Travel\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelPackages extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_packages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 200);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_packages');
    }
}
