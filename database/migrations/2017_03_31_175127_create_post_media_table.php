<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->string('media_url', 255);
            $table->enum('type', ['VIEDO', 'IMAGE']);

            // Foreign Keys
            $table->integer('post_id')->unsigned();
            
            
            // Relationships
            $table->foreign('post_id')->references('id')->on('post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_media');
    }
}
