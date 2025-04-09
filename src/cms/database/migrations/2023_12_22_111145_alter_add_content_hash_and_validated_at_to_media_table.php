<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', static function (Blueprint $table): void {
            $table->string('content_hash', 64)->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->foreignUuid('organisation_id')->nullable()->constrained('organisations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('media', static function (Blueprint $table): void {
            $table->dropColumn('content_hash');
            $table->dropForeign(['organisation_id']);
            $table->dropColumn('organisation_id');
        });
    }
};
