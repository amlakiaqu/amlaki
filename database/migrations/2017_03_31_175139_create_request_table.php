<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Foreign Keys
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();

            // Relationships
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('category_id')->references('id')->on('category');

            // Created & Updated Columns
            $table->timestamps(); // Add created_at & updated_at columns
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request');
    }
}
