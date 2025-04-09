<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', static function (Blueprint $table): void {
            $table->string('email')->nullable();
            $table->json('context')->nullable();
            $table->text('pii_context')->nullable();
            $table->timestamp('created_at');
            $table->string('event_code')->nullable();
            $table->string('action_code')->nullable();
            $table->string('source')->nullable();
            $table->boolean('allowed_admin_view')->default(false);
            $table->boolean('failed')->default(false);
            $table->text('failed_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::drop('audit_logs');
    }
};
