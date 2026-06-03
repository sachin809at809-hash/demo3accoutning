<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('ecommerce_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name'); // Store name in case product is deleted
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 4)->default(0.00); // Price at time of purchase
            $table->decimal('total', 15, 4)->default(0.00);
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('order_id')->references('id')->on('ecommerce_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('ecommerce_products')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecommerce_order_items');
    }
}
