<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAiEnabledToOmnichatChannelsTable extends Migration
{
    public function up()
    {
        Schema::table('omnichat_channels', function (Blueprint $table) {
            $table->boolean('is_ai_enabled')->default(false)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('omnichat_channels', function (Blueprint $table) {
            $table->dropColumn('is_ai_enabled');
        });
    }
}
