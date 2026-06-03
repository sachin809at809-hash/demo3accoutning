<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceProductImagesTable extends Migration
{
    public function up()
    {
        Schema::create('ecommerce_product_images', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('product_id');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('product_id')->references('id')->on('ecommerce_products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecommerce_product_images');
    }
}
