<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ai_assistant_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('file_path');
            $table->string('status')->default('uploaded');
            $table->string('document_type')->nullable();
            $table->json('extracted_data')->nullable();
            $table->unsignedInteger('document_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index('status');
            $table->index('document_id');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_assistant_uploads');
    }
};
