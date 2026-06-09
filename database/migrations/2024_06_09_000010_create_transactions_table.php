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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->restrictOnDelete();
            $table->string('transactionable_type', 100);
            $table->uuid('transactionable_id');
            $table->enum('type', ['donation', 'refund', 'fee', 'payout', 'adjustment']);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MYR');
            $table->decimal('balance_after', 10, 2);
            $table->string('description', 500);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'type']);
            $table->index(['transactionable_type', 'transactionable_id']);
            $table->index(['organization_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};