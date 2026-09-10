<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_drafts', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('resource_class');
            $table->uuid('record_id')->nullable();
            $table->uuid('draft_key');
            $table->text('payload');
            $table->timestamps();

            $table->unique(['user_id', 'resource_class', 'draft_key']);
        });
    }
};
