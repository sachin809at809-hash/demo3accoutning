<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmnichatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omnichat_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('conversation_id');
            $table->string('external_id')->nullable(); // The message ID from the provider
            $table->string('direction'); // incoming or outgoing
            $table->text('body')->nullable();
            $table->string('type')->default('text'); // text, image, file, template
            $table->json('attachments')->nullable();
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('conversation_id')->references('id')->on('omnichat_conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('omnichat_messages');
    }
}
