<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->string('title', 255);
            $table->string('image', 255);      

            // Foreign Keys
            $table->integer('user_id')->unsigned();
            $table->integer('verified_by')->unsigned()->nullable();
            $table->integer('category_id')->unsigned();

            // Relationships
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('verified_by')->references('id')->on('user');
            $table->foreign('category_id')->references('id')->on('category');

            // Created & Updated Columns
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
