<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('public_website_snapshot_entries', static function (Blueprint $table): void {
            $table->renameColumn('last_public_website_check_id', 'last_static_website_check_id');
        });

        Schema::rename('public_website_checks', 'static_website_checks');
        Schema::rename('public_website_snapshot_entries', 'static_website_snapshot_entries');
    }
};
