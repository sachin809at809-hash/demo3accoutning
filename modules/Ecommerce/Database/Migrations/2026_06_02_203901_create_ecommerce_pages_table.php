<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommercePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_pages', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('slug')->unique(); // e.g., 'home', 'about-us'
            $table->string('title');
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->longText('components')->nullable(); // JSON from GrapesJS
            $table->longText('styles')->nullable(); // JSON from GrapesJS
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecommerce_pages');
    }
}
