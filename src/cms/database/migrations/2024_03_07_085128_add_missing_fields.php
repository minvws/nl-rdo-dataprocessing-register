<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('measures_description')->nullable();
        });

        Schema::create('public_website_builds', static function (Blueprint $table): void {
            $table->dateTime('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures_description');
        });

        Schema::dropIfExists('public_website_builds');
    }
};
