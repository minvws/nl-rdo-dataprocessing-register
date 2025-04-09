<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsible_legal_entity', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('organisations', static function (Blueprint $table): void {
            $table->foreignUuid('responsible_legal_entity_id')
                ->nullable()
                ->constrained('responsible_legal_entity');
        });
    }

    public function down(): void
    {
        Schema::table('organisation', static function (Blueprint $table): void {
            $table->dropColumn('responsible_legal_entity_id');
        });

        Schema::dropIfExists('responsible_legal_entity');
    }
};
