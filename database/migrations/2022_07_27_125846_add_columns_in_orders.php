<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('cert_fee_amt')->after('hazardous_amt')->default(0)->nullable();
            $table->longText('payment_response')->after('payment_status')->nullable();
            $table->longText('shipment_response')->after('shippment_status')->nullable();
            $table->longText('qbo_response')->after('shipment_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_response');
            $table->dropColumn('shipment_response');
            $table->dropColumn('qbo_response');
            $table->dropColumn('cert_fee_amt');
        });
    }
}
