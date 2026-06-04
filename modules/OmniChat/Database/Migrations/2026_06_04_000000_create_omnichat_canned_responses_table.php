<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmnichatCannedResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('omnichat_canned_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('title');
            $table->string('shortcut')->nullable(); // e.g. /hello
            $table->text('body');
            $table->timestamps();
            
            $table->index('company_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('omnichat_canned_responses');
    }
}
