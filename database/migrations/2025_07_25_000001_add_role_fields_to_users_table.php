<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'exam_admin', 'moderator', 'marker', 'data_entry', 'viewer'])->default('viewer')->after('name');
            $table->string('phone')->nullable()->after('role');
            $table->boolean('force_password_change')->default(false)->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('force_password_change');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'force_password_change', 'last_login_at', 'last_login_ip', 'is_active']);
        });
    }
};
