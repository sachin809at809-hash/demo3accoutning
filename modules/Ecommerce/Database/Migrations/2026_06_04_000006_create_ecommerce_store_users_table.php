<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEcommerceStoreUsersTable extends Migration {
    public function up() {
        Schema::create('ecommerce_store_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('ecommerce_store_users'); }
}