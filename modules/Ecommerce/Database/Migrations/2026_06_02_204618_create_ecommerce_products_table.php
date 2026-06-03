<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceProductsTable extends Migration
{
    public function up()
    {
        Schema::create('ecommerce_products', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 15, 4)->default(0.00);
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('category_id')->references('id')->on('ecommerce_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecommerce_products');
    }
}
