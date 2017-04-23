<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Other Columns
            $table->string('name', 64);
            $table->string('username', 64);
            $table->string('email', 191);
            $table->string('password', 128);
            $table->boolean('is_admin')->default(false);
            $table->string('image', 255)->nullable();
            $table->string('phone', 10);
            $table->string('api_token', 60)->nullable();
            $table->rememberToken();

            // Add Indexes
            $table->index('username');
            $table->unique('username');
            $table->unique('email');
            $table->unique('phone');

            // Created & Updated Columns
            $table->timestamps(); // Add created_at & updated_at columns

            // Soft Delete Columns
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}