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
        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->restrictOnDelete();
            $table->foreignUuid('campaign_id')->nullable()->constrained('campaigns')->cascadeOnDelete();
            $table->foreignUuid('donor_id')->nullable()->constrained('donors')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MYR');
            $table->enum('status', ['pending', 'succeeded', 'failed', 'refunded'])->default('pending');
            $table->enum('payment_method', ['card', 'bank_transfer', 'ewallet', 'cash']);
            $table->string('payment_gateway', 50);
            $table->string('gateway_transaction_id');
            $table->json('gateway_response')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone', 50)->nullable();
            $table->text('donor_message')->nullable();
            $table->decimal('refunded_amount', 10, 2)->default(0.00);
            $table->string('refund_reason', 500)->nullable();
            $table->boolean('receipt_sent')->default(false);
            $table->string('receipt_url', 500)->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('source_url', 500)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status', 'created_at']);
            $table->index(['campaign_id', 'status', 'created_at']);
            $table->index(['donor_id', 'created_at']);
            $table->index('gateway_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};