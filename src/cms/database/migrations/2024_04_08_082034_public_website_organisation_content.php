<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->text('public_website_content')->nullable();
        });

        Schema::table('public_website', static function (Blueprint $table): void {
            $table->dropColumn('organisation_content');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->dropColumn('public_website_content');
        });

        Schema::table('public_website', static function (Blueprint $table): void {
            $table->text('organisation_content')->nullable();
        });
    }
};
