<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEcommerceReviewsTable extends Migration {
    public function up() {
        Schema::create('ecommerce_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('author_name');
            $table->integer('rating');
            $table->text('comment');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('ecommerce_reviews'); }
}