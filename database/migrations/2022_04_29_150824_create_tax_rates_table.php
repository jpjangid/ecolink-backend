<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country_code')->nullable();
            $table->string('state_code')->nullable();
            $table->integer('zip')->nullable();
            $table->string('city')->nullable();
            $table->float('rate')->nullable();
            $table->string('tax_name')->nullable();
            $table->tinyInteger('priority')->nullable();
            $table->tinyInteger('compound')->nullable();
            $table->tinyInteger('shipping')->nullable();
            $table->string('tax_class')->nullable();
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
        Schema::dropIfExists('tax_rates');
    }
}
