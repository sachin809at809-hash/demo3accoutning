<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('ecommerce_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('parent_id')->references('id')->on('ecommerce_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecommerce_categories');
    }
}
