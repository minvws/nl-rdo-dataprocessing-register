<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('processing_records');

        Schema::table('tags', static function (Blueprint $table): void {
            $table->dropColumn('type');
        });
    }
};
