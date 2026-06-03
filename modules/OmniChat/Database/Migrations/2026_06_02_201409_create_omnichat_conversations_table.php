<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmnichatConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omnichat_conversations', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('channel_id');
            $table->integer('contact_id')->nullable(); // Link to Akaunting Contact
            $table->string('external_id')->nullable(); // Thread ID in FB, WhatsApp sender number, etc
            $table->string('name')->nullable(); // Customer name
            $table->string('avatar')->nullable();
            $table->string('status')->default('open'); // open, closed, snoozed
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('channel_id')->references('id')->on('omnichat_channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('omnichat_conversations');
    }
}
