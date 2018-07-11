<?php namespace Aldea\Travel\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelUsers extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_customers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            
            // Basic information
            $table->string('fullname', 150);
            $table->date('birthdate')->nullable();
            $table->string('birthplace', 150)->nullable();
            $table->string('ci', 50)->nullable();
            $table->date('ci_expiration')->nullable();
            $table->string('passport', 50)->nullable();
            $table->date('passport_expiration')->nullable();
            $table->string('ruc', 50)->nullable();
            $table->string('business_name', 150)->nullable();
            $table->integer('city_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->text('additional_information')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('city_id')->references('id')->on('aldea_travel_countries');
            $table->foreign('group_id')->references('id')->on('aldea_travel_groups');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_customers');
    }
}
