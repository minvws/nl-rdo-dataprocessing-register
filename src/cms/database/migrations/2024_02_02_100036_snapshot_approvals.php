<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snapshot_approvals', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')
                ->constrained('snapshots')
                ->cascadeOnDelete();
            $table->foreignUuid('requested_by')
                ->nullable()
                ->constrained('users');
            $table->foreignUuid('assigned_to')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('status')->default('unknown');
            $table->dateTime('notified_at')->nullable();
            $table->timestamps();

            $table->unique(['snapshot_id', 'assigned_to']);
        });

        Schema::create('snapshot_approval_logs', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')
                ->constrained('snapshots')
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->constrained('users');
            $table->json('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot_approvals');
        Schema::dropIfExists('snapshot_approval_logs');
    }
};
