<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Retireve The POST_TYPES & DEFAULT_POST_TYPE from constants to user in migration
        $postTypes = Config::get('constants.POST_TYPES');
        $defaultPostType =  Config::get('constants.DEFAULT_POST_TYPE');

        
        Schema::create('property', function (Blueprint $table) use ($postTypes, $defaultPostType) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->string('title');
            $table->string('hint', 128);
            $table->string('code', 64);
            $table->enum('value_type', $postTypes)->default($defaultPostType);
            $table->text('possible_values')->nullable();

            // Indexes
            $table->index('code');
            $table->unique('code');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove property table
        Schema::dropIfExists('property');
    }
}
