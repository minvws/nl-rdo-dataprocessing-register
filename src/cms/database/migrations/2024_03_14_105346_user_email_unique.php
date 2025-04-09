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
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropUnique('users_email_unique');
        });

        DB::statement("CREATE UNIQUE INDEX users_email_unique ON users (email) WHERE deleted_at IS NULL;");
    }
};
