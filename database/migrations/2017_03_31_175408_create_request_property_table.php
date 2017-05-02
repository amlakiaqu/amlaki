<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_property', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->text('value');            

            // Foreign Keys
            $table->integer('request_id')->unsigned();
            $table->integer('property_id')->unsigned();

            // Relationships
            $table->foreign('request_id')->references('id')->on('request');
            $table->foreign('property_id')->references('id')->on('property');
            
            // Indexes
            $table->unique(['request_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_property');
    }
}
