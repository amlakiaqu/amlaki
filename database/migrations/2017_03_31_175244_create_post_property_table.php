<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_property', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->text('value');            

            // Foreign Keys
            $table->integer('post_id')->unsigned();
            $table->integer('property_id')->unsigned();

            // Relationships
            $table->foreign('post_id')->references('id')->on('post');
            $table->foreign('property_id')->references('id')->on('property');
            
            // Indexes
            $table->unique(['post_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_property');
    }
}
