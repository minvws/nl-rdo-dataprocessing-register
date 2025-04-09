<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processing_records', static function (Blueprint $table): void {
            $table->string('number')->nullable()->unique(
                IndexNameTruncater::unique($table->getTable(), 'number'),
            );
        });
    }

    public function down(): void
    {
        Schema::table('processing_records', static function (Blueprint $table): void {
            $table->dropColumn('number');
        });
    }
};
