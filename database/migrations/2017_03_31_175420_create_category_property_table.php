<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_property', function (Blueprint $table) {
            // Other Columns
            $table->boolean('required')->default(false);            

            // Foreign Keys
            $table->integer('category_id')->unsigned();
            $table->integer('property_id')->unsigned();

            // Relationships
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('property_id')->references('id')->on('property');
            
            // Indexes
            $table->unique(['category_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_property');
    }
}
