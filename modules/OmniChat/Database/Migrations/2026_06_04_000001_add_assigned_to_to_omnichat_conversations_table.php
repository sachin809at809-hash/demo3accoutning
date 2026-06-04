<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedToToOmnichatConversationsTable extends Migration
{
    public function up()
    {
        Schema::table('omnichat_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable()->after('last_message_at');
        });
    }

    public function down()
    {
        Schema::table('omnichat_conversations', function (Blueprint $table) {
            $table->dropColumn('assigned_to');
        });
    }
}
