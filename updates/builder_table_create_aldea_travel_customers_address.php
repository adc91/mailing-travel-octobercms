<?php namespace Aldea\Travel\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAldeaTravelCustomerAddress extends Migration
{
    public function up()
    {
        Schema::create('aldea_travel_customers_address', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('address', 200);
            $table->tinyInteger('type');
            $table->integer('customer_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')->references('id')->on('aldea_travel_customers');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('aldea_travel_customers_address');
    }
}
