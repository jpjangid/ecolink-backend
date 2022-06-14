<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wp_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id');
            $table->tinyInteger('tax_exempt')->default(0);
            $table->bigInteger('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('landmark')->nullable();
            $table->string('profile_image')->nullable();
            $table->tinyInteger('flag')->default(0);
            $table->tinyInteger('email_verified')->default(0);
            $table->string('api_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
