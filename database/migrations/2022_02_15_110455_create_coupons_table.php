<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('cat_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->float('min_order_amount')->nullable();
            $table->float('max_order_amount')->nullable();
            $table->datetime('offer_start')->nullable();
            $table->datetime('offer_end')->nullable();
            $table->string('days')->nullable();
            $table->integer('coupon_limit')->nullable();
            $table->integer('times_applied')->nullable();
            $table->string('disc_type')->nullable();
            $table->integer('discount')->nullable();
            $table->tinyInteger('show_in_front')->default('0');
            $table->tinyInteger('status')->default('0');
            $table->tinyInteger('flag')->default('0');
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
        Schema::dropIfExists('coupons');
    }
}
