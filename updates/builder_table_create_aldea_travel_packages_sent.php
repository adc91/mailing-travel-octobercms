<?php namespace Aldea\Travel\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelPackagesSent extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_packages_sent', function($table)
        {
            $table->engine = 'InnoDB';
            $table->string('subject', 200);
            $table->integer('customer_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')->references('id')->on('aldea_travel_customers');
            $table->foreign('package_id')->references('id')->on('aldea_travel_packages');
            $table->foreign('group_id')->references('id')->on('aldea_travel_groups');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_packages_sent');
    }
}
