<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('public_website_builds');

        Schema::create('public_website_checks', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->dateTime('build_date');
            $table->json('content');

            $table->timestamps();
        });

        Schema::create('public_website_snapshot_entries', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('last_public_website_check_id');
            $table->foreignUuid('snapshot_id');
            $table->string('url');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();

            $table->timestamps();
        });
    }
};
