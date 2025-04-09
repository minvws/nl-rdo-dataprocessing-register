<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->dropUnique('organisations_slug_unique');
        });

        DB::statement("CREATE UNIQUE INDEX organisations_slug ON organisations (slug) WHERE deleted_at IS NULL;");
    }
};
