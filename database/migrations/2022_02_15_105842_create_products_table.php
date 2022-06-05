<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wp_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->nullable();
            $table->string('variant')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->string('hsn')->nullable();
            $table->text('short_desc')->nullable();
            $table->longtext('description')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount')->nullable();
            $table->float('regular_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->text('image')->nullable();
            $table->string('alt')->nullable();
            $table->string('tag')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->tinyInteger('in_stock')->default('0');
            $table->unsignedInteger('stock')->nullable();
            $table->unsignedInteger('low_stock')->nullable();
            $table->unsignedInteger('sold_individually')->nullable();
            $table->unsignedInteger('minimum_qty')->nullable();
            $table->float('weight')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->string('shipping_class')->nullable();
            $table->tinyInteger('insurance')->default('0');
            $table->tinyInteger('hazardous')->default('0');
            $table->text('head_schema')->nullable();
            $table->text('body_schema')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->text('og_image')->nullable();
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
        Schema::dropIfExists('products');
    }
}
