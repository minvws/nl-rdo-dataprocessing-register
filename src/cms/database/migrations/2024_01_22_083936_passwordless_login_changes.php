<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('password');
        });

        Schema::create('user_login_tokens', static function (Blueprint $table): void {
            $table->uuid('token')->unique()->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->string('password');
        });

        Schema::drop('user_login_tokens');
    }
};
