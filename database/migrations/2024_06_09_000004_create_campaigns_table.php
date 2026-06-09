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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->decimal('target_amount', 10, 2)->default(0.00);
            $table->decimal('raised_amount', 10, 2)->default(0.00);
            $table->integer('donor_count')->default(0);
            $table->enum('status', ['draft', 'active', 'paused', 'completed'])->default('draft');
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            $table->string('category', 100);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('embed_code')->nullable();
            $table->json('meta')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'status', 'created_at']);
            $table->index(['organization_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};