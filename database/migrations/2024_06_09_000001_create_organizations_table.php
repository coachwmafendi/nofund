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
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['mosque', 'ngo', 'charity', 'community', 'individual']);
            $table->string('logo_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone', 50)->nullable();
            $table->json('address')->nullable();
            $table->string('timezone', 50)->default('Asia/Kuala_Lumpur');
            $table->string('currency', 3)->default('MYR');
            $table->enum('status', ['active', 'suspended', 'deactivated'])->default('active');
            $table->uuid('plan_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};