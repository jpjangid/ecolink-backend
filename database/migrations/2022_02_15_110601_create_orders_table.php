<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->float('order_amount');
            $table->float('discount_applied')->nullable();
            $table->float('service_charge_applied')->nullable();
            $table->float('total_amount');
            $table->integer('no_items')->default(0);
            $table->string('billing_name');
            $table->string('billing_mobile');
            $table->string('billing_email');
            $table->string('billing_address');
            $table->string('billing_country');
            $table->string('billing_state');
            $table->string('billing_city');
            $table->string('billing_zip');
            $table->string('billing_landmark')->nullable();
            $table->string('shipping_name');
            $table->string('shipping_mobile');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_address');
            $table->string('shipping_country');
            $table->string('shipping_state');
            $table->string('shipping_city');
            $table->string('shipping_zip');
            $table->string('shipping_landmark')->nullable();
            $table->string('order_status');
            $table->string('payment_via')->nullable();
            $table->float('payment_amount')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('shippment_via')->nullable();
            $table->string('shippment_status')->nullable();
            $table->text('order_comments')->nullable();
            $table->float('wallet_amount')->nullable();
            $table->string('coupon_id')->nullable();
            $table->float('coupon_discount')->nullable();
            $table->tinyInteger('flag')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
