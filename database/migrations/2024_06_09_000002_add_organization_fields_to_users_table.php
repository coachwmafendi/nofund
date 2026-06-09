<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url', 500)->nullable()->after('password');
            $table->enum('role', ['super_admin', 'admin', 'manager', 'viewer'])->default('viewer')->after('avatar_url');
            $table->enum('status', ['active', 'invited', 'deactivated'])->default('active')->after('role');
            $table->foreignUuid('organization_id')->nullable()->constrained('organizations')->cascadeOnDelete()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('organization_id');

            $table->index('organization_id');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropIndex(['role']);
            $table->dropColumn(['avatar_url', 'role', 'status', 'organization_id', 'last_login_at']);
        });
    }
};