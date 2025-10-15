<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_device_tokens', function (Blueprint $table) {
            $table->timestamp('last_used_at')->nullable()->after('device_type');
            $table->timestamp('registered_at')->nullable()->after('last_used_at');
            $table->index(['last_used_at', 'device_type']);
        });
    }

    public function down()
    {
        Schema::table('user_device_tokens', function (Blueprint $table) {
            $table->dropIndex(['last_used_at', 'device_type']);
            $table->dropColumn(['last_used_at', 'registered_at']);
        });
    }
};
