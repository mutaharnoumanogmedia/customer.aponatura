<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar_path')) {
                $table->string('avatar_path')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('UTC')->after('avatar_path');
            }
            if (!Schema::hasColumn('users', 'notifications')) {
                $table->json('notifications')->nullable()->after('timezone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'notifications')) $table->dropColumn('notifications');
            if (Schema::hasColumn('users', 'timezone')) $table->dropColumn('timezone');
            if (Schema::hasColumn('users', 'avatar_path')) $table->dropColumn('avatar_path');
        });
    }
};
